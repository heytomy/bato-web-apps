<?php

namespace App\Entity;

use App\Repository\AppelsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppelsRepository::class)]
class Appels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Veuillez ajouter un nom')]
    #[ORM\Column(length: 30)]
    private ?string $Nom = null;

    #[ORM\Column(length: 50)]
    private ?string $Adr = null;

    #[ORM\Column(length: 15)]
    private ?string $CP = null;

    #[ORM\Column(length: 25)]
    private ?string $Ville = null;
    
    #[ORM\Column(length: 30)]
    private ?string $Tel = null;

    #[ORM\Column(name:"Email",length: 50)]
    private ?string $Email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $rdvDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $rdvHeure = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isUrgent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getAdr(): ?string
    {
        return $this->Adr;
    }

    public function setAdr(string $Adr): self
    {
        $this->Adr = $Adr;

        return $this;
    }

    public function getCP(): ?string
    {
        return $this->CP;
    }

    public function setCP(string $CP): self
    {
        $this->CP = $CP;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRdvDate(): ?\DateTimeInterface
    {
        return $this->rdvDate;
    }

    public function setRdvDate(\DateTimeInterface $rdvDate): self
    {
        $this->rdvDate = $rdvDate;

        return $this;
    }

    public function getRdvHeure(): ?\DateTimeInterface
    {
        return $this->rdvHeure;
    }

    public function setRdvHeure(\DateTimeInterface $rdvHeure): self
    {
        $this->rdvHeure = $rdvHeure;

        return $this;
    }

    public function isUrgent(): ?bool
    {
        return $this->isUrgent;
    }

    public function setIsUrgent(?bool $isUrgent): self
    {
        $this->isUrgent = $isUrgent;

        return $this;
    }
}
