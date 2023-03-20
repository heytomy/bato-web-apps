<?php

namespace App\Entity;

use App\Repository\AppelsSAVRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $rdvDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $rdvHeure = null;

    #[ORM\OneToMany(mappedBy: 'AppelsSAV', targetEntity: TicketUrgents::class)]
    private Collection $ticketUrgents;

    public function __construct()
    {
        $this->ticketUrgents = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, TicketUrgents>
     */
    public function getTicketUrgents(): Collection
    {
        return $this->ticketUrgents;
    }

    public function addTicketUrgent(TicketUrgents $ticketUrgent): self
    {
        if (!$this->ticketUrgents->contains($ticketUrgent)) {
            $this->ticketUrgents->add($ticketUrgent);
            $ticketUrgent->setAppelsSAV($this);
        }

        return $this;
    }

    public function removeTicketUrgent(TicketUrgents $ticketUrgent): self
    {
        if ($this->ticketUrgents->removeElement($ticketUrgent)) {
            // set the owning side to null (unless already changed)
            if ($ticketUrgent->getAppelsSAV() === $this) {
                $ticketUrgent->setAppelsSAV(null);
            }
        }

        return $this;
    }
}
