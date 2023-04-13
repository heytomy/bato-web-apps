<?php

namespace App\Entity;

use App\Repository\ChantierAppsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChantierAppsRepository::class)]
class ChantierApps
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Id')]
    private ?int $id = null;

    #[ORM\Column(name: 'Libellé', length: 50, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(name: 'Description', type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(name: 'Date_debut', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: 'Date_Fin', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(name: 'Adresse', length: 100, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(name: 'CP', length: 10, nullable: true)]
    private ?string $cp = null;

    #[ORM\Column(name: 'Ville', length: 100, nullable: true)]
    private ?string $ville = null;

    #[ORM\ManyToOne(inversedBy: 'chantierApps')]
    #[ORM\JoinColumn(name: 'ID_statut', referencedColumnName: 'Id', nullable: true)]
    private ?StatutChantier $statut = null;

    #[ORM\OneToMany(mappedBy: 'codeChantier', targetEntity: PhotosChantier::class, orphanRemoval: true)]
    private Collection $photosChantiers;

    #[ORM\OneToMany(mappedBy: 'codeChantier', targetEntity: CommentairesChantier::class, orphanRemoval: true)]
    private Collection $commentairesChantiers;

    #[ORM\OneToMany(mappedBy: 'codeChantier', targetEntity: RepCommentairesChantier::class, orphanRemoval: true)]
    private Collection $repCommentairesChantiers;

    #[ORM\Column(name: 'ID_Devis', nullable: true)]
    private ?int $idDevis = null;

    #[ORM\ManyToOne(inversedBy: 'chantierApps')]
    #[ORM\JoinColumn(name: 'CodeClient', referencedColumnName: 'Code', nullable: false)]
    private ?ClientDef $codeClient = null;

    public function __construct()
    {
        $this->photosChantiers = new ArrayCollection();
        $this->commentairesChantiers = new ArrayCollection();
        $this->repCommentairesChantiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getStatut(): ?StatutChantier
    {
        return $this->statut;
    }

    public function setStatut(?StatutChantier $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, PhotosChantier>
     */
    public function getPhotosChantiers(): Collection
    {
        return $this->photosChantiers;
    }

    public function addPhotosChantier(PhotosChantier $photosChantier): self
    {
        if (!$this->photosChantiers->contains($photosChantier)) {
            $this->photosChantiers->add($photosChantier);
            $photosChantier->setCodeChantier($this);
        }

        return $this;
    }

    public function removePhotosChantier(PhotosChantier $photosChantier): self
    {
        if ($this->photosChantiers->removeElement($photosChantier)) {
            // set the owning side to null (unless already changed)
            if ($photosChantier->getCodeChantier() === $this) {
                $photosChantier->setCodeChantier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentairesChantier>
     */
    public function getCommentairesChantiers(): Collection
    {
        return $this->commentairesChantiers;
    }

    public function addCommentairesChantier(CommentairesChantier $commentairesChantier): self
    {
        if (!$this->commentairesChantiers->contains($commentairesChantier)) {
            $this->commentairesChantiers->add($commentairesChantier);
            $commentairesChantier->setCodeChantier($this);
        }

        return $this;
    }

    public function removeCommentairesChantier(CommentairesChantier $commentairesChantier): self
    {
        if ($this->commentairesChantiers->removeElement($commentairesChantier)) {
            // set the owning side to null (unless already changed)
            if ($commentairesChantier->getCodeChantier() === $this) {
                $commentairesChantier->setCodeChantier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RepCommentairesChantier>
     */
    public function getRepCommentairesChantiers(): Collection
    {
        return $this->repCommentairesChantiers;
    }

    public function addRepCommentairesChantier(RepCommentairesChantier $repCommentairesChantier): self
    {
        if (!$this->repCommentairesChantiers->contains($repCommentairesChantier)) {
            $this->repCommentairesChantiers->add($repCommentairesChantier);
            $repCommentairesChantier->setCodeChantier($this);
        }

        return $this;
    }

    public function removeRepCommentairesChantier(RepCommentairesChantier $repCommentairesChantier): self
    {
        if ($this->repCommentairesChantiers->removeElement($repCommentairesChantier)) {
            // set the owning side to null (unless already changed)
            if ($repCommentairesChantier->getCodeChantier() === $this) {
                $repCommentairesChantier->setCodeChantier(null);
            }
        }

        return $this;
    }

    public function getIdDevis(): ?int
    {
        return $this->idDevis;
    }

    public function setIdDevis(?int $idDevis): self
    {
        $this->idDevis = $idDevis;

        return $this;
    }

    public function getCodeClient(): ?ClientDef
    {
        return $this->codeClient;
    }

    public function setCodeClient(?ClientDef $codeClient): self
    {
        $this->codeClient = $codeClient;

        return $this;
    }
}
