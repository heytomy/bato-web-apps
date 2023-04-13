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

    #[ORM\Column(nullable: true)]
    private ?bool $isUrgent = null;

    #[ORM\ManyToOne(targetEntity: DefAppsUtilisateur::class)]
    #[ORM\JoinColumn(name:"ID_Utilisateur", referencedColumnName:"ID_Utilisateur", nullable:true)]
    private $ID_Utilisateur;

    #[ORM\ManyToOne(inversedBy: 'appels')]
    #[ORM\JoinColumn(name:"CodeClient", referencedColumnName:"Code", nullable:true)]
    private ?ClientDef $CodeClient = null;

    #[ORM\ManyToOne(inversedBy: 'appels')]
    #[ORM\JoinColumn(name:"CodeContrat", referencedColumnName:"Code", nullable:true)]
    private ?Contrat $CodeContrat = null;

    #[ORM\OneToOne(inversedBy: 'appels', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name:"ID_rdv", referencedColumnName:"id", nullable:true)]
    private ?Calendrier $rdv = null;

    #[ORM\OneToMany(mappedBy: 'idAppel', targetEntity: PhotosAppels::class)]
    private Collection $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
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

    public function isUrgent(): ?bool
    {
        return $this->isUrgent;
    }

    public function setIsUrgent(?bool $isUrgent): self
    {
        $this->isUrgent = $isUrgent;

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

    public function getCodeContrat(): ?Contrat
    {
        return $this->CodeContrat;
    }

    public function setCodeContrat(?Contrat $CodeContrat): self
    {
        $this->CodeContrat = $CodeContrat;

        return $this;
    }

    public function getRdv(): ?Calendrier
    {
        return $this->rdv;
    }

    public function setRdv(?Calendrier $rdv): self
    {
        $this->rdv = $rdv;

        return $this;
    }

    /**
     * @return Collection<int, PhotosAppels>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(PhotosAppels $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setIdAppel($this);
        }

        return $this;
    }

    public function removePhoto(PhotosAppels $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getIdAppel() === $this) {
                $photo->setIdAppel(null);
            }
        }

        return $this;
    }
}
