<?php

namespace App\Entity;

use App\Repository\ResultatDicomRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: ResultatDicomRepository::class)]
#[ORM\Table(name: 'resultat_dicom')]
class ResultatDicom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Examen::class, inversedBy: 'resultatsDicom')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Examen $examen = null;

    #[ORM\Column(length: 255)]
    private string $orthancInstanceId;

    #[ORM\Column(length: 255)]
    private string $studyInstanceUid;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seriesInstanceUid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modality = null; // CT, MRI, RX...

    #[ORM\Column]
    private DateTimeImmutable $receivedAt;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $orthancUrl = null;

    public function __construct()
    {
        $this->receivedAt = new DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getExamen(): ?Examen { return $this->examen; }
    public function setExamen(?Examen $examen): static { $this->examen = $examen; return $this; }

    public function getOrthancInstanceId(): string { return $this->orthancInstanceId; }
    public function setOrthancInstanceId(string $id): static { $this->orthancInstanceId = $id; return $this; }

    public function getStudyInstanceUid(): string { return $this->studyInstanceUid; }
    public function setStudyInstanceUid(string $uid): static { $this->studyInstanceUid = $uid; return $this; }

    public function getSeriesInstanceUid(): ?string { return $this->seriesInstanceUid; }
    public function setSeriesInstanceUid(?string $uid): static { $this->seriesInstanceUid = $uid; return $this; }

    public function getModality(): ?string { return $this->modality; }
    public function setModality(?string $modality): static { $this->modality = $modality; return $this; }

    public function getReceivedAt(): DateTimeImmutable { return $this->receivedAt; }

    public function getOrthancUrl(): ?string { return $this->orthancUrl; }
    public function setOrthancUrl(?string $url): static { $this->orthancUrl = $url; return $this; }
}
