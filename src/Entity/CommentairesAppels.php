<?php

namespace App\Entity;

use App\Repository\CommentairesAppelsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesAppelsRepository::class)]
class CommentairesAppels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ID_Commentaire')]
    private ?int $id = null;

    #[ORM\Column(name: 'Commentaire_Appels', type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(name: 'Date_Com', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_com = null;

    #[ORM\Column(name: 'Nom', length: 30, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $codeAppels = null;

    #[ORM\Column(name: 'CodeClient', length: 8, nullable: true)]
    private ?string $codeClient = null;

    #[ORM\ManyToOne(inversedBy: 'commentairesAppels')]
    #[ORM\JoinColumn(name: 'ID_Utilisateur', referencedColumnName: 'ID_Utilisateur', nullable: false)]
    private ?DefAppsUtilisateur $owner = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: RepCommentairesAppels::class, orphanRemoval: true)]
    private Collection $replies;

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

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->date_com;
    }

    public function setDateCom(\DateTimeInterface $date_com): self
    {
        $this->date_com = $date_com;

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

    public function getCodeAppels(): ?int
    {
        return $this->codeAppels;
    }

    public function setCodeAppels(int $codeAppels): self
    {
        $this->codeAppels = $codeAppels;

        return $this;
    }

    public function getCodeClient(): ?string
    {
        return $this->codeClient;
    }

    public function setCodeClient(?string $codeClient): self
    {
        $this->codeClient = $codeClient;

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

    /**
     * @return Collection<int, RepCommentairesAppels>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(RepCommentairesAppels $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies->add($reply);
            $reply->setParent($this);
        }

        return $this;
    }

    public function removeReply(RepCommentairesAppels $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getParent() === $this) {
                $reply->setParent(null);
            }
        }

        return $this;
    }
}
