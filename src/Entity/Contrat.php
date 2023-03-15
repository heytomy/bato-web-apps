<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Code", length: 8)]
    private ?string $id = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $Note = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    #[ORM\JoinColumn(name:"CodeClient", referencedColumnName: "Code", nullable: false)]
    private ?ClientDef $CodeClient = null;


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->Note;
    }

    public function setNote(string $Note): self
    {
        $this->Note = $Note;

        return $this;
    }

    public function getCodeClient(): ?ClientDef
    {
        return $this->CodeClient;
    }

    public function setCodeClient(?ClientDef $CodeClient): self
    {
        $this->CodeClient = $CodeClient;

        return $this;
    }
}