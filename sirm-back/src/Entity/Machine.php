<?php
namespace App\Entity;

use App\Enum\StatutMachine;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Machine
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private int $id;

    #[ORM\Column(length: 100)]
    private string $nom;

    #[ORM\Column(length: 10)]
    private string $modalite; // CT, MR, CR, US, DX

    #[ORM\Column(length: 64)]
    private string $aeTitle; // identifiant DICOM de la machine

    #[ORM\Column(length: 45)]
    private string $adresseIP;

    #[ORM\Column(type: 'string', enumType: StatutMachine::class)]
    private StatutMachine $statut = StatutMachine::DISPONIBLE;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    // Getters / Setters
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $n): self { $this->nom = $n; return $this; }
    public function getModalite(): string { return $this->modalite; }
    public function setModalite(string $m): self { $this->modalite = $m; return $this; }
    public function getAeTitle(): string { return $this->aeTitle; }
    public function setAeTitle(string $ae): self { $this->aeTitle = $ae; return $this; }
    public function getAdresseIP(): string { return $this->adresseIP; }
    public function setAdresseIP(string $ip): self { $this->adresseIP = $ip; return $this; }
    public function getStatut(): StatutMachine { return $this->statut; }
    public function setStatut(StatutMachine $s): self { $this->statut = $s; return $this; }

    public function getDateDebut(): ?\DateTimeInterface { return $this->dateDebut; }
    public function setDateDebut(?\DateTimeInterface $d): self { $this->dateDebut = $d; return $this; }

    public function getDateFin(): ?\DateTimeInterface { return $this->dateFin; }
    public function setDateFin(?\DateTimeInterface $d): self { $this->dateFin = $d; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $desc): self { $this->description = $desc; return $this; }

    public function isDisponible(): bool
    {
        return $this->statut === StatutMachine::DISPONIBLE;
    }
}