<?php

namespace App\Entity;

use App\Repository\AppelsSAVRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppelsSAVRepository::class)]
class AppelsSAV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id")]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'appelsSAVs')]
    #[ORM\JoinColumn(name:'CodeContrat', referencedColumnName:'Code', nullable: false)]
    private ?Contrat $contrats = null;

    #[ORM\ManyToOne(inversedBy: 'appelsSAVs')]
    #[ORM\JoinColumn(name:'CodeClient', referencedColumnName:'Code', nullable: false)]
    private ?ClientDef $client = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContrats(): ?Contrat
    {
        return $this->contrats;
    }

    public function setContrats(?Contrat $contrats): self
    {
        $this->contrats = $contrats;

        return $this;
    }

    public function getClient(): ?ClientDef
    {
        return $this->client;
    }

    public function setClient(?ClientDef $client): self
    {
        $this->client = $client;

        return $this;
    }
}
