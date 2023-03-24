<?php

namespace App\Entity;

use App\Repository\DefAppsUtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DefAppsUtilisateurRepository::class)]
class DefAppsUtilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "ID_Utilisateur", type: "integer")]
    public ?int $ID_Utilisateur = null;

    #[Assert\NotBlank(message: "Veuillez ajouter un nom")]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Nom = null;

    #[Assert\NotBlank(message: "Veuillez ajouter un prénom")]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Adresse = null;

    #[Assert\Regex(
        pattern: "\d{0,6}", 
        message: "Le code postal doit être des nombres"
        )]
    #[ORM\Column(length: 6, nullable: true)]
    private ?string $CP = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Ville = null;

    #[Assert\Regex(
        pattern: "\d{0,10}", 
        message: "Veuillez saisir un numéro"
        )]
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $Tel_1 = null;

    #[Assert\Regex(
        pattern: "\d{0,10}", 
        message: "Veuillez saisir un numéro"
        )]
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $Tel_2 = null;

    #[Assert\NotBlank(message: "Veuillez ajouter un mail")]
    #[Assert\Email(
        message: 'L\'email {{ value }} n\'est pas valide'
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Mail = null;

    #[ORM\OneToMany(mappedBy: 'ID_Utilisateur', targetEntity: AppsUtilisateur::class, orphanRemoval: true)]
    private Collection $comptes;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: CommentairesSAV::class)]
    private Collection $commentairesSAVs;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: RepCommentairesSAV::class)]
    private Collection $repCommentairesSAVs;

    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->commentairesSAVs = new ArrayCollection();
        $this->repCommentairesSAVs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->ID_Utilisateur;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(?string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(?string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(?string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getCP(): ?string
    {
        return $this->CP;
    }

    public function setCP(?string $CP): self
    {
        $this->CP = $CP;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(?string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getTel1(): ?string
    {
        return $this->Tel_1;
    }

    public function setTel1(?string $Tel_1): self
    {
        $this->Tel_1 = $Tel_1;

        return $this;
    }

    public function getTel2(): ?string
    {
        return $this->Tel_2;
    }

    public function setTel2(?string $Tel_2): self
    {
        $this->Tel_2 = $Tel_2;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->Mail;
    }

    public function setMail(?string $Mail): self
    {
        $this->Mail = $Mail;

        return $this;
    }

    /**
     * @return Collection<int, AppsUtilisateur>
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(AppsUtilisateur $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes->add($compte);
            $compte->setIDUtilisateur($this);
        }

        return $this;
    }

    public function removeCompte(AppsUtilisateur $compte): self
    {
        if ($this->comptes->removeElement($compte)) {
            // set the owning side to null (unless already changed)
            if ($compte->getIDUtilisateur() === $this) {
                $compte->setIDUtilisateur(null);
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
            $commentairesSAV->setOwner($this);
        }

        return $this;
    }

    public function removeCommentairesSAV(CommentairesSAV $commentairesSAV): self
    {
        if ($this->commentairesSAVs->removeElement($commentairesSAV)) {
            // set the owning side to null (unless already changed)
            if ($commentairesSAV->getOwner() === $this) {
                $commentairesSAV->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RepCommentairesSAV>
     */
    public function getRepCommentairesSAVs(): Collection
    {
        return $this->repCommentairesSAVs;
    }

    public function addRepCommentairesSAV(RepCommentairesSAV $repCommentairesSAV): self
    {
        if (!$this->repCommentairesSAVs->contains($repCommentairesSAV)) {
            $this->repCommentairesSAVs->add($repCommentairesSAV);
            $repCommentairesSAV->setOwner($this);
        }

        return $this;
    }

    public function removeRepCommentairesSAV(RepCommentairesSAV $repCommentairesSAV): self
    {
        if ($this->repCommentairesSAVs->removeElement($repCommentairesSAV)) {
            // set the owning side to null (unless already changed)
            if ($repCommentairesSAV->getOwner() === $this) {
                $repCommentairesSAV->setOwner(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom();
    }
}
