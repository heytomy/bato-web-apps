<?php

namespace App\Entity;

use App\Repository\CommentairesSAVRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentairesSAVRepository::class)]
class CommentairesSAV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ID_Commentaire')]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Veuillez ajouter un commentaire')]
    #[Assert\Length(
        min: 2,
        max: 250,
        minMessage: 'Il faut avoir un minimum de {{ limit }} caractères',
        maxMessage: 'Il ne faut pas passer de {{ limit }} caractères',
    )]
    #[ORM\Column(name: 'Commentaire_SAV', type: Types::TEXT, nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column(name: 'Date_Com', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_com = null;

    #[ORM\ManyToOne(inversedBy: 'commentairesSAVs')]
    #[ORM\JoinColumn(name: 'Code', referencedColumnName: 'Code', nullable: true)]
    private ?Contrat $codeContrat = null;

    #[ORM\Column(name: 'Nom', length: 30, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(name: 'CodeClient', length: 8)]
    private ?string $codeClient = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: RepCommentairesSAV::class, orphanRemoval: true)]
    private Collection $replies;

    #[ORM\ManyToOne(inversedBy: 'commentairesSAVs')]
    #[ORM\JoinColumn(name: 'ID_Utilisateur', referencedColumnName: 'ID_Utilisateur', nullable: false)]
    private ?DefAppsUtilisateur $owner = null;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->date_com;
    }

    public function setDateCom(?\DateTimeInterface $date_com): self
    {
        $this->date_com = $date_com;

        return $this;
    }

    public function getCodeContrat(): ?Contrat
    {
        return $this->codeContrat;
    }

    public function setCodeContrat(?Contrat $codeContrat): self
    {
        $this->codeContrat = $codeContrat;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodeClient(): ?string
    {
        return $this->codeClient;
    }

    public function setCodeClient(string $codeClient): self
    {
        $this->codeClient = $codeClient;

        return $this;
    }

    /**
     * @return Collection<int, RepCommentairesSAV>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReplies(RepCommentairesSAV $replies): self
    {
        if (!$this->replies->contains($replies)) {
            $this->replies->add($replies);
            $replies->setParent($this);
        }

        return $this;
    }

    public function removeReplies(RepCommentairesSAV $replies): self
    {
        if ($this->replies->removeElement($replies)) {
            // set the owning side to null (unless already changed)
            if ($replies->getParent() === $this) {
                $replies->setParent(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?DefAppsUtilisateur
    {
        return $this->owner;
    }

    public function setOwner(?DefAppsUtilisateur $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
