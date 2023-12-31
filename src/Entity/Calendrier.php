<?php

namespace App\Entity;

use App\Repository\CalendrierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendrierRepository::class)]
#[ORM\Table(name: 'RDV_Calendrier')]
class Calendrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    private ?bool $allDay = null;

    #[ORM\OneToOne(inversedBy: 'rdv', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name:"ID_Appels", referencedColumnName:"id", nullable:true, onDelete:"CASCADE")]
    private ?Appels $appels = null;

    #[ORM\OneToOne(inversedBy: 'rdv', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name:"ID_Chantier", referencedColumnName:"Id", nullable:true, onDelete:"CASCADE")]
    private ?ChantierApps $Chantier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function isAllDay(): ?bool
    {
        return $this->allDay;
    }

    public function setAllDay(bool $allDay): self
    {
        $this->allDay = $allDay;

        return $this;
    }

    public function getAppels(): ?Appels
    {
        return $this->appels;
    }

    public function setAppels(?Appels $appels): self
    {
        $this->appels = $appels;

        return $this;
    }

    public function getChantier(): ?ChantierApps
    {
        return $this->Chantier;
    }

    public function setChantier(?ChantierApps $Chantier): self
    {
        $this->Chantier = $Chantier;

        return $this;
    }
}
