<?php
namespace App\Entity;

use App\Enum\StatutExamen;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\ExamenRepository::class)]
class Examen
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(length: 10)]
    private string $type;

    #[ORM\Column(enumType: StatutExamen::class)]
    private StatutExamen $statut = StatutExamen::PLANIFIE;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateModifStatut = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $compteRendu = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $datePrevue = null;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'examens')]
    #[ORM\JoinColumn(nullable: false)]
    private Patient $patient;

    #[ORM\ManyToOne(targetEntity: Machine::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Machine $machine;
#[ORM\ManyToOne(targetEntity: Utilisateur::class)]
#[ORM\JoinColumn(nullable: true)]
private ?Utilisateur $medecin = null;

public function getMedecin(): ?Utilisateur
{
    return $this->medecin;
}

public function setMedecin(?Utilisateur $u): self
{
    $this->medecin = $u;
    return $this;
}

    #[ORM\OneToOne(mappedBy: 'examen', targetEntity: ResultatDicom::class, cascade: ['persist'])]
    private ?ResultatDicom $resultatDicom = null;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function updateStatut(StatutExamen $nouveau): self
    {
        $this->statut = $nouveau;
        $this->dateModifStatut = new \DateTime();
        return $this;
    }
// ...
#[ORM\Column(length: 255, nullable: true)]
private ?string $studyInstanceUid = null;

public function getStudyInstanceUid(): ?string { return $this->studyInstanceUid; }
public function setStudyInstanceUid(?string $uid): self { $this->studyInstanceUid = $uid; return $this; }
// ...
    public function getId(): int { return $this->id; }

    public function getAccessionNumber(): string
    {
        return sprintf('ACC%06d', $this->id);
    }

    public function getDate(): \DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $d): self { $this->date = $d; return $this; }
    public function getType(): string { return $this->type; }
    public function setType(string $t): self { $this->type = $t; return $this; }
    public function getStatut(): StatutExamen { return $this->statut; }
    public function getDateModifStatut(): ?\DateTimeInterface { return $this->dateModifStatut; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): self { $this->description = $d; return $this; }
    public function getCompteRendu(): ?string { return $this->compteRendu; }
    public function setCompteRendu(?string $cr): self { $this->compteRendu = $cr; return $this; }
    public function getDatePrevue(): ?\DateTimeInterface { return $this->datePrevue; }
    public function setDatePrevue(?\DateTimeInterface $d): self { $this->datePrevue = $d; return $this; }
    public function getPatient(): Patient { return $this->patient; }
    public function setPatient(Patient $p): self { $this->patient = $p; return $this; }
    public function getMachine(): Machine { return $this->machine; }
    public function setMachine(Machine $m): self { $this->machine = $m; return $this; }

    public function getResultatDicom(): ?ResultatDicom { return $this->resultatDicom; }
}