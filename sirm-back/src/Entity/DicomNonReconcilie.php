<?php

namespace App\Entity;

use App\Repository\DicomNonReconcilieRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: DicomNonReconcilieRepository::class)]
#[ORM\Table(name: 'dicom_non_reconcilie')]
class DicomNonReconcilie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $orthancInstanceId;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientIdDicom = null;   // PatientID tag DICOM

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patientNomDicom = null;  // PatientName tag DICOM

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $studyInstanceUid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modality = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $metadonneesBrutes = null; // payload complet Orthanc

    #[ORM\Column]
    private DateTimeImmutable $receivedAt;

    #[ORM\Column(length: 50)]
    private string $statut = 'EN_ATTENTE'; // EN_ATTENTE | RECONCILIE | REJETE

    #[ORM\ManyToOne(targetEntity: Examen::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Examen $examenReconcilie = null;

    public function __construct()
    {
        $this->receivedAt = new DateTimeImmutable();
    }
    // ...
#[ORM\ManyToOne(targetEntity: Examen::class)]
#[ORM\JoinColumn(nullable: true)]




    public function getId(): ?int { return $this->id; }

    public function getOrthancInstanceId(): string { return $this->orthancInstanceId; }
    public function setOrthancInstanceId(string $id): static { $this->orthancInstanceId = $id; return $this; }

    public function getPatientIdDicom(): ?string { return $this->patientIdDicom; }
    public function setPatientIdDicom(?string $id): static { $this->patientIdDicom = $id; return $this; }

    public function getPatientNomDicom(): ?string { return $this->patientNomDicom; }
    public function setPatientNomDicom(?string $nom): static { $this->patientNomDicom = $nom; return $this; }

    public function getStudyInstanceUid(): ?string { return $this->studyInstanceUid; }
    public function setStudyInstanceUid(?string $uid): static { $this->studyInstanceUid = $uid; return $this; }

    public function getModality(): ?string { return $this->modality; }
    public function setModality(?string $m): static { $this->modality = $m; return $this; }

    public function getMetadonneesBrutes(): ?array { return $this->metadonneesBrutes; }
    public function setMetadonneesBrutes(?array $data): static { $this->metadonneesBrutes = $data; return $this; }

    public function getReceivedAt(): DateTimeImmutable { return $this->receivedAt; }

    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }

    public function getExamenReconcilie(): ?Examen { return $this->examenReconcilie; }
    public function setExamenReconcilie(?Examen $examen): static { $this->examenReconcilie = $examen; return $this; }
}