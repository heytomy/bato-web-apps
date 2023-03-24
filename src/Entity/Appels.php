<?php

namespace App\Entity;

use App\Repository\AppelsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppelsRepository::class)]
class Appels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
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

    #[ORM\OneToMany(mappedBy: 'AppelsUrgents', targetEntity: TicketUrgents::class)]
    private Collection $ticketUrgents;

     #[ORM\ManyToOne(targetEntity: DefAppsUtilisateur::class)]
     #[ORM\JoinColumn(name:"ID_Utilisateur", referencedColumnName:"ID_Utilisateur", nullable:false)]
    private $ID_Utilisateur;

    public function __construct()
    {
        $this->ticketUrgents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIDUtilisateur(): ?DefAppsUtilisateur
    {
        return $this->ID_Utilisateur;
    }


    public function setIDUtilisateur(?DefAppsUtilisateur $ID_Utilisateur): self
    {
        $this->ID_Utilisateur = $ID_Utilisateur;

        return $this;
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
            $ticketUrgent->setAppelsUrgents($this);
        }

        return $this;
    }

    public function removeTicketUrgent(TicketUrgents $ticketUrgent): self
    {
        if ($this->ticketUrgents->removeElement($ticketUrgent)) {
            // set the owning side to null (unless already changed)
            if ($ticketUrgent->getAppelsUrgents() === $this) {
                $ticketUrgent->setAppelsUrgents(null);
            }
        }

        return $this;
    }
}
