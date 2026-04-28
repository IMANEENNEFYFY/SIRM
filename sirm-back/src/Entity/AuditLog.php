<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AuditLog
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private int $id;

    #[ORM\Column(length: 100)]
    private string $action; // CREATION_PATIENT, RECEPTION_DICOM, etc.

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateHeure;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $adresseIP = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $details = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    private ?Utilisateur $utilisateur = null;

    public function getResume(): string
    {
        return sprintf('[%s] %s — %s', 
            $this->dateHeure->format('d/m/Y H:i'), 
            $this->action, 
            $this->details ?? ''
        );
    }

    // Getters / Setters
    public function getId(): int { return $this->id; }
    public function getAction(): string { return $this->action; }
    public function setAction(string $a): self { $this->action = $a; return $this; }
    public function getDateHeure(): \DateTimeInterface { return $this->dateHeure; }
    public function setDateHeure(\DateTimeInterface $d): self { $this->dateHeure = $d; return $this; }
    public function getAdresseIP(): ?string { return $this->adresseIP; }
    public function setAdresseIP(?string $ip): self { $this->adresseIP = $ip; return $this; }
    public function getDetails(): ?string { return $this->details; }
    public function setDetails(?string $d): self { $this->details = $d; return $this; }
    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $u): self { $this->utilisateur = $u; return $this; }
}