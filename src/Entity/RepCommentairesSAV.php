<?php

namespace App\Entity;

use App\Repository\RepCommentairesSAVRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepCommentairesSAVRepository::class)]
class RepCommentairesSAV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ID_Reponse', length: 8)]
    private ?int $id = null;

    #[ORM\Column(name: 'Commentaire_SAV',type: Types::TEXT)]
    private ?string $commentaire_SAV = null;

    #[ORM\Column(name: 'Date_Com', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_com = null;

    #[ORM\ManyToOne(inversedBy: 'repCommentairesSAVs')]
    #[ORM\JoinColumn(name: 'CodeClient')]
    private ?AppsUtilisateur $codeClient = null;

    #[ORM\Column(name: 'Nom',length: 30, nullable: true)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'replies')]
    #[ORM\JoinColumn(name: 'Parent', referencedColumnName:'ID_Commentaire', nullable: false)]
    private ?CommentairesSAV $parent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaireSAV(): ?string
    {
        return $this->commentaire_SAV;
    }

    public function setCommentaireSAV(string $commentaire_SAV): self
    {
        $this->commentaire_SAV = $commentaire_SAV;

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

    public function getCodeClient(): ?AppsUtilisateur
    {
        return $this->codeClient;
    }

    public function setCodeClient(?AppsUtilisateur $codeClient): self
    {
        $this->codeClient = $codeClient;

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
}
