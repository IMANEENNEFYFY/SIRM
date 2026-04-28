<?php

namespace App\Service;

use App\Entity\DicomNonReconcilie;
use App\Entity\Examen;
use App\Entity\ResultatDicom;
use App\Enum\StatutExamen;
use App\Repository\ExamenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OrthancService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ExamenRepository       $examenRepository,
        private readonly LoggerInterface        $logger,
        private readonly HttpClientInterface    $httpClient,
    ) {}

    // ================================================================== //
    //  WEBHOOK — réception des instances DICOM depuis Orthanc             //
    // ================================================================== //

    public function handleNewInstance(array $payload): void
    {
        $instanceId     = $payload['instanceId']       ?? null;
        $studyUid       = $payload['studyInstanceUID'] ?? null;
        $patientIdDicom = $payload['patientId']        ?? null;
        $patientNom     = $payload['patientName']      ?? null;
        $modality       = $payload['modality']         ?? null;

        if (!$instanceId) {
            $this->logger->warning('[DICOM] Payload reçu sans instanceId', $payload);
            return;
        }

        $examen = $this->trouverExamen($patientIdDicom, $studyUid);

        if ($examen) {
            $this->reconcilier($examen, $instanceId, $studyUid, $modality, $payload);
        } else {
            $this->mettreEnAttente($instanceId, $patientIdDicom, $patientNom, $studyUid, $modality, $payload);
        }
    }

    // ================================================================== //
    //  RECONCILIATION                                                     //
    // ================================================================== //

    private function trouverExamen(?string $patientIdDicom, ?string $studyUid): ?Examen
    {
        if ($studyUid) {
            $examen = $this->examenRepository->findOneBy(['studyInstanceUid' => $studyUid]);
            if ($examen) return $examen;
        }

        if ($patientIdDicom) {
            $examen = $this->examenRepository->findExamenEnCoursByPatientId($patientIdDicom);
            if ($examen) return $examen;
        }

        return null;
    }

    private function reconcilier(
        Examen  $examen,
        string  $instanceId,
        ?string $studyUid,
        ?string $modality,
        array   $payload
    ): void {
        $resultat = new ResultatDicom();
        $resultat->setExamen($examen);
        $resultat->setOrthancInstanceId($instanceId);
        $resultat->setStudyInstanceUid($studyUid ?? '');
        $resultat->setModality($modality);
        $resultat->setOrthancUrl('http://localhost:8042/instances/' . $instanceId);

        $examen->updateStatut(StatutExamen::RECU);

        $this->em->persist($resultat);
        $this->em->flush();

        $this->logger->info('[DICOM] Instance réconciliée', [
            'instanceId' => $instanceId,
            'examenId'   => $examen->getId(),
        ]);
    }

    private function mettreEnAttente(
        string  $instanceId,
        ?string $patientIdDicom,
        ?string $patientNom,
        ?string $studyUid,
        ?string $modality,
        array   $payload
    ): void {
        $nonRec = new DicomNonReconcilie();
        $nonRec->setOrthancInstanceId($instanceId);
        $nonRec->setPatientIdDicom($patientIdDicom);
        $nonRec->setPatientNomDicom($patientNom);
        $nonRec->setStudyInstanceUid($studyUid);
        $nonRec->setModality($modality);
        $nonRec->setMetadonneesBrutes($payload);

        $this->em->persist($nonRec);
        $this->em->flush();

        $this->logger->warning('[DICOM] Instance non réconciliée — mise en attente', [
            'instanceId' => $instanceId,
            'patientId'  => $patientIdDicom,
            'patientNom' => $patientNom,
        ]);
    }

    // ================================================================== //
    //  WORKLIST — génération et suppression                               //
    // ================================================================== //

    public function genererWorklist(Examen $examen): bool
    {
        try {
            $data = $this->preparerWorklistData($examen);
            return $this->ecrireFichierWorklist($data);
        } catch (\Throwable $e) {
            $this->logger->error('[DICOM] Erreur génération worklist', [
                'examenId' => $examen->getId(),
                'error'    => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function preparerWorklistData(Examen $examen): array
    {
        $patient = $examen->getPatient();
        $machine = $examen->getMachine();

        if (!$examen->getStudyInstanceUid()) {
            $examen->setStudyInstanceUid($this->genererStudyInstanceUid());
            $this->em->flush();
        }

        return [
            'PatientName'                       => strtoupper($patient->getNom() ?? '') . '^' . ($patient->getPrenom() ?? ''),
            'PatientID'                         => $patient->getPatientId() ?? '',
            'PatientBirthDate'                  => $patient->getDateNaissance()?->format('Ymd') ?? '',
            'PatientSex'                        => $patient->getSexe() ?? 'O',
            'AccessionNumber'                   => $examen->getAccessionNumber() ?? '',
            'StudyInstanceUID'                  => $examen->getStudyInstanceUid() ?? '',
            'Modality'                          => $machine->getModalite() ?? '',
            'ScheduledStationAETitle'           => $machine->getAeTitle() ?? '',
            'ScheduledProcedureStepStartDate'   => $examen->getDatePrevue()?->format('Ymd') ?? '',
            'ScheduledProcedureStepStartTime'   => $examen->getDatePrevue()?->format('His') ?? '',
            'ScheduledPerformingPhysicianName'  => $examen->getMedecin()?->getNom() ?? '',
            'RequestedProcedureDescription'     => $examen->getType() ?? '',
            'RequestedProcedureID'              => $examen->getAccessionNumber() ?? '',
            'ScheduledProcedureStepID'          => $examen->getAccessionNumber() ?? '',
           
        ];
    }

    private function ecrireFichierWorklist(array $data): bool
    {
        $worklistDir = rtrim($_ENV['ORTHANC_WORKLIST_DIR'] ?? 'C:/orthanc/worklists', '/');
        $accession   = $data['AccessionNumber'];

        if (!is_dir($worklistDir)) {
            if (!mkdir($worklistDir, 0755, true)) {
                $this->logger->error('[DICOM] Impossible de créer le dossier worklist', [
                    'dir' => $worklistDir,
                ]);
                return false;
            }
        }

        $dumpFile = $worklistDir . '/' . $accession . '.dump';
        $wlFile   = $worklistDir . '/' . $accession . '.wl';

        $dumpContent = $this->genererDumpContent($data);

        if (file_put_contents($dumpFile, $dumpContent) === false) {
            $this->logger->error('[DICOM] Impossible d\'écrire le fichier dump', [
                'file' => $dumpFile,
            ]);
            return false;
        }

        // Convertir en DICOM binaire avec dump2dcm (DCMTK)
        $dump2dcm = $_ENV['DUMP2DCM_PATH'] ?? 'dump2dcm';
        $cmd = sprintf(
            '%s %s %s 2>&1',
            escapeshellcmd($dump2dcm),
            escapeshellarg($dumpFile),
            escapeshellarg($wlFile)
        );
        exec($cmd, $output, $returnCode);

        // Nettoyer le fichier dump temporaire
        @unlink($dumpFile);

        if ($returnCode !== 0) {
            $this->logger->warning('[DICOM] dump2dcm indisponible — fichier .wl écrit en texte brut', [
                'command'    => $cmd,
                'returnCode' => $returnCode,
                'output'     => implode("\n", $output),
                'file'       => $wlFile,
            ]);
            // Fallback : écrire le dump texte directement pour tests
            file_put_contents($wlFile, $dumpContent);
        }

        $this->logger->info('[DICOM] Fichier worklist créé', [
            'file'      => $wlFile,
            'accession' => $accession,
        ]);

        return true;
    }

    private function genererDumpContent(array $data): string
    {
        $patientName = $this->escapeDicom($data['PatientName']);
        $patientId   = $this->escapeDicom($data['PatientID']);
        $birthDate   = $this->escapeDicom($data['PatientBirthDate']);
        $sex         = $this->escapeDicom($data['PatientSex']);
        $accession   = $this->escapeDicom($data['AccessionNumber']);
        $studyUid    = $this->escapeDicom($data['StudyInstanceUID']);
        $modality    = $this->escapeDicom($data['Modality']);
        $aeTitle     = $this->escapeDicom($data['ScheduledStationAETitle']);
        $startDate   = $this->escapeDicom($data['ScheduledProcedureStepStartDate']);
        $startTime   = $this->escapeDicom($data['ScheduledProcedureStepStartTime']);
        $physician   = $this->escapeDicom($data['ScheduledPerformingPhysicianName']);
        $description = $this->escapeDicom($data['RequestedProcedureDescription']);
        $stepDesc    = $this->escapeDicom($data['ScheduledProcedureStepDescription']);
        $reqProcId   = $this->escapeDicom($data['RequestedProcedureID']);
        $stepId      = $this->escapeDicom($data['ScheduledProcedureStepID']);

        return <<<DUMP
# Worklist generated by SIRM-RIS
(0008,0005) CS [ISO_IR 192]
(0010,0010) PN [{$patientName}]
(0010,0020) LO [{$patientId}]
(0010,0030) DA [{$birthDate}]
(0010,0040) CS [{$sex}]
(0020,000d) UI [{$studyUid}]
(0008,0050) SH [{$accession}]
(0032,1060) LO [{$description}]
(0040,1001) SH [{$reqProcId}]
(0040,1003) SH [MED]
(0040,a504) SQ
(fffe,e000) -
(0008,0060) CS [{$modality}]
(0040,0001) AE [{$aeTitle}]
(0040,0002) DA [{$startDate}]
(0040,0003) TM [{$startTime}]
(0040,0006) PN [{$physician}]
(0040,0007) LO [{$stepDesc}]
(0040,0009) SH [{$stepId}]
(0040,0010) SH []
(0040,0011) SH []
(fffe,e00d) -
(fffe,e0dd) -
DUMP;
    }

    private function escapeDicom(?string $value): string
    {
        if ($value === null) return '';
        $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value);
        $value = str_replace(['[', ']'], ['(', ')'], $value);
        return trim($value);
    }

    public function supprimerWorklist(string $accessionNumber): bool
    {
        try {
            $worklistDir = rtrim($_ENV['ORTHANC_WORKLIST_DIR'] ?? 'C:/orthanc/worklists', '/');
            $wlFile      = $worklistDir . '/' . $accessionNumber . '.wl';
            $dumpFile    = $worklistDir . '/' . $accessionNumber . '.dump';

            foreach ([$wlFile, $dumpFile] as $file) {
                if (file_exists($file)) {
                    unlink($file);
                    $this->logger->info('[DICOM] Fichier worklist supprimé', ['file' => $file]);
                }
            }

            return true;

        } catch (\Exception $e) {
            $this->logger->error('[DICOM] Erreur suppression worklist', [
                'accessionNumber' => $accessionNumber,
                'error'           => $e->getMessage(),
            ]);
            return false;
        }
    }

    // ================================================================== //
    //  ORTHANC REST API — utilitaires                                     //
    // ================================================================== //

    public function verifierConnexion(): bool
    {
        try {
            $response = $this->httpClient->request('GET', $this->getOrthancBaseUrl() . '/plugins', [
                'timeout' => 5,
            ]);

            $plugins = json_decode($response->getContent(), true) ?? [];

            if (!in_array('worklists', $plugins)) {
                $this->logger->warning('[DICOM] Plugin worklists non chargé dans Orthanc', [
                    'plugins' => $plugins,
                ]);
                return false;
            }

            return true;

        } catch (\Exception $e) {
            $this->logger->error('[DICOM] Orthanc inaccessible', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getInstancesParStudy(string $studyInstanceUid): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->getOrthancBaseUrl() . '/tools/find', [
                'json' => [
                    'Level'  => 'Study',
                    'Query'  => ['StudyInstanceUID' => $studyInstanceUid],
                    'Expand' => false,
                ],
                'timeout' => 10,
            ]);

            return json_decode($response->getContent(), true) ?? [];

        } catch (\Exception $e) {
            $this->logger->error('[DICOM] Erreur recherche instances Orthanc', [
                'studyUid' => $studyInstanceUid,
                'error'    => $e->getMessage(),
            ]);
            return [];
        }
    }

    // ================================================================== //
    //  HELPERS                                                            //
    // ================================================================== //

    private function genererStudyInstanceUid(): string
    {
        $timestamp = (int)(microtime(true) * 10000);
        $random    = mt_rand(1000, 9999);
        return "1.2.826.0.1.3680043.2.1000.{$timestamp}.{$random}";
    }

    private function getOrthancBaseUrl(): string
    {
        return rtrim($_ENV['ORTHANC_URL'] ?? 'http://localhost:8042', '/');
    }
}