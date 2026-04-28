<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Patient
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private int $id;

    #[ORM\Column(length: 20, unique: true)]
    private string $patientId; // format P-AAAAMMJJ-XXXX

    #[ORM\Column(length: 100)]
    private string $nom;

    #[ORM\Column(length: 100)]
    private string $prenom;

    #[ORM\Column(length: 20, unique: true)]
    private string $cin;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dateNaissance;

    #[ORM\Column(length: 10)]
    private string $sexe;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column]
    private bool $actif = true;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Examen::class)]
    private Collection $examens;

    public function __construct()
    {
        $this->examens = new ArrayCollection();
    }

    public function getNomComplet(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getAge(): int
    {
        return (new \DateTime())->diff($this->dateNaissance)->y;
    }

    // Getters / Setters
    public function getId(): int { return $this->id; }
    public function getPatientId(): string { return $this->patientId; }
    public function setPatientId(string $pid): self { $this->patientId = $pid; return $this; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $p): self { $this->prenom = $p; return $this; }
    public function getCin(): string { return $this->cin; }
    public function setCin(string $cin): self { $this->cin = $cin; return $this; }
    public function getDateNaissance(): \DateTimeInterface { return $this->dateNaissance; }
    public function setDateNaissance(\DateTimeInterface $d): self { $this->dateNaissance = $d; return $this; }
    public function getSexe(): string { return $this->sexe; }
    public function setSexe(string $s): self { $this->sexe = $s; return $this; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(?string $t): self { $this->telephone = $t; return $this; }
    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $a): self { $this->adresse = $a; return $this; }
    public function isActif(): bool { return $this->actif; }
    public function setActif(bool $a): self { $this->actif = $a; return $this; }
    public function getExamens(): Collection { return $this->examens; }
}