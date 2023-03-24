<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'contrats', targetEntity: AppelsSAV::class)]
    private Collection $appelsSAVs;

    #[ORM\OneToMany(mappedBy: 'codeContrat', targetEntity: CommentairesSAV::class)]
    private Collection $commentairesSAVs;

    #[ORM\OneToMany(mappedBy: 'Code', targetEntity: PhotosSAV::class)]
    private Collection $photosSAVs;

    public function __construct()
    {
        $this->appelsSAVs = new ArrayCollection();
        $this->commentairesSAVs = new ArrayCollection();
        $this->photosSAVs = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, AppelsSAV>
     */
    public function getAppelsSAVs(): Collection
    {
        return $this->appelsSAVs;
    }

    public function addAppelsSAV(AppelsSAV $appelsSAV): self
    {
        if (!$this->appelsSAVs->contains($appelsSAV)) {
            $this->appelsSAVs->add($appelsSAV);
            $appelsSAV->setContrats($this);
        }
        return $this;
    }
    
    public function removeAppelsSAV(AppelsSAV $appelsSAV): self
    {
        if ($this->appelsSAVs->removeElement($appelsSAV)) {
            // set the owning side to null (unless already changed)
            if ($appelsSAV->getContrats() === $this) {
                $appelsSAV->setContrats(null);
            }
        }
        return $this;
    }
    

    /**
     * @return Collection<int, CommentairesSAV>
     */
    public function getCommentairesSAVs(): Collection
    {
        return $this->commentairesSAVs;
    }

    public function addCommentairesSAV(CommentairesSAV $commentairesSAV): self
    {
        if (!$this->commentairesSAVs->contains($commentairesSAV)) {
            $this->commentairesSAVs->add($commentairesSAV);
            $commentairesSAV->setCodeContrat($this);
        }

        return $this;
    }

    public function removeCommentairesSAV(CommentairesSAV $commentairesSAV): self
    {
        if ($this->commentairesSAVs->removeElement($commentairesSAV)) {
            // set the owning side to null (unless already changed)
            if ($commentairesSAV->getCodeContrat() === $this) {
                $commentairesSAV->setCodeContrat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PhotosSAV>
     */
    public function getPhotosSAVs(): Collection
    {
        return $this->photosSAVs;
    }

    public function addPhotosSAV(PhotosSAV $photosSAV): self
    {
        if (!$this->photosSAVs->contains($photosSAV)) {
            $this->photosSAVs->add($photosSAV);
            $photosSAV->setCode($this);
        }

        return $this;
    }

    public function removePhotosSAV(PhotosSAV $photosSAV): self
    {
        if ($this->photosSAVs->removeElement($photosSAV)) {
            // set the owning side to null (unless already changed)
            if ($photosSAV->getCode() === $this) {
                $photosSAV->setCode(null);
            }
        }

        return $this;
    }
}