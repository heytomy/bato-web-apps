<?php

namespace App\Entity;

use App\Repository\CommentairesChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentairesChantierRepository::class)]
class CommentairesChantier
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
    #[ORM\Column(name: 'Commentaire_Chantier', type: Types::TEXT, nullable: false)]
    private ?string $contenu = null;

    #[ORM\Column(name: 'Date_Com', type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $date_com = null;

    #[ORM\Column(name: 'Nom', length: 30, nullable: true)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'commentairesChantiers')]
    #[ORM\JoinColumn(name: 'CodeChantier', referencedColumnName: 'Id', nullable: false)]
    private ?ChantierApps $codeChantier = null;

    #[ORM\ManyToOne(inversedBy: 'commentairesChantiers')]
    #[ORM\JoinColumn(name: 'ID_Utilisateur', referencedColumnName: 'ID_Utilisateur', nullable: false)]
    private ?DefAppsUtilisateur $owner = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: RepCommentairesChantier::class, orphanRemoval: true)]
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

    public function getCodeChantier(): ?ChantierApps
    {
        return $this->codeChantier;
    }

    public function setCodeChantier(?ChantierApps $codeChantier): self
    {
        $this->codeChantier = $codeChantier;

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
     * @return Collection<int, RepCommentairesChantier>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(RepCommentairesChantier $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies->add($reply);
            $reply->setParent($this);
        }

        return $this;
    }

    public function removeReply(RepCommentairesChantier $reply): self
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
