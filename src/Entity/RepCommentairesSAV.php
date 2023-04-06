<?php

namespace App\Entity;

use App\Repository\RepCommentairesSAVRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RepCommentairesSAVRepository::class)]
class RepCommentairesSAV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ID_Reponse', length: 8)]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Veuillez ajouter une réponse')]
    #[Assert\Length(
        min: 2,
        max: 250,
        minMessage: 'Il faut avoir un minimum de {{ limit }} caractères',
        maxMessage: 'Il ne faut pas passer de {{ limit }} caractères',
    )]
    #[ORM\Column(name: 'Commentaire_SAV',type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(name: 'Date_Com', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_com = null;

    #[ORM\Column(name: 'Nom',length: 30, nullable: true)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'replies')]
    #[ORM\JoinColumn(name: 'Parent', referencedColumnName:'ID_Commentaire', nullable: false)]
    private ?CommentairesSAV $parent = null;

    #[ORM\Column(name: 'CodeClient', length: 8, nullable: true)]
    private ?string $codeClient = null;

    #[ORM\ManyToOne(inversedBy: 'repCommentairesSAVs')]
    #[ORM\JoinColumn(name: 'ID_Utilisateur', referencedColumnName: 'ID_Utilisateur', nullable: false)]
    private ?DefAppsUtilisateur $owner = null;

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

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getParent(): ?CommentairesSAV
    {
        return $this->parent;
    }

    public function setParent(?CommentairesSAV $parent): self
    {
        $this->parent = $parent;

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
}
