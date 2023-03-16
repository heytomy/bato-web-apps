<?php

namespace App\Entity;

use App\Repository\CommentairesSAVRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesSAVRepository::class)]
class CommentairesSAV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "ID_Commentaire", length: 8)]
    private ?int $id = null;

    #[ORM\Column(name: 'Commentaire_SAV',type: Types::TEXT, nullable: true)]
    private ?string $commentaire_SAV = null;

    #[ORM\Column(name: 'Date_Com', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_com = null;

    #[ORM\ManyToOne(inversedBy: 'commentairesSAVs')]
    #[ORM\JoinColumn(name: 'Code', referencedColumnName: 'Code', nullable: false)]
    private ?Contrat $codeContrat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaireSAV(): ?string
    {
        return $this->commentaire_SAV;
    }

    public function setCommentaireSAV(?string $Commentaire_SAV): self
    {
        $this->commentaire_SAV = $Commentaire_SAV;

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
}
