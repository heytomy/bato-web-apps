<?php

namespace App\Entity;

use App\Repository\PhotosSAVRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: PhotosSAVRepository::class)]
class PhotosSAV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdPhoto', length: 30)]
    private ?string $id = null;

    #[ORM\Column(name: 'URL_Photo', length: 255, nullable: true)]
    private ?string $URL_Photo = null;

    #[ORM\Column(name: 'Date_Photo', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_Photo = null;

    #[ORM\Column(name: 'Nom', length: 30, nullable: true)]
    private ?string $Nom = null;

    #[ORM\ManyToOne(inversedBy: 'photosSAVs')]
    #[ORM\JoinColumn(name:"Code", referencedColumnName: "Code", nullable: true)]
    private ?Contrat $Code = null;

    #[ORM\Column(name: 'CodeClient', length: 8, nullable: true)]
    private ?string $CodeClient = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getURLPhoto(): ?string
    {
        return $this->URL_Photo;
    }

    public function setURLPhoto(?string $URL_Photo): self
    {
        $this->URL_Photo = $URL_Photo;

        return $this;
    }

    public function getDatePhoto(): ?\DateTimeInterface
    {
        return $this->Date_Photo;
    }

    public function setDatePhoto(?\DateTimeInterface $Date_Photo): self
    {
        $this->Date_Photo = $Date_Photo;

        return $this;
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

    public function getCode(): ?Contrat
    {
        return $this->Code;
    }

    public function setCode(?Contrat $Code): self
    {
        $this->Code = $Code;

        return $this;
    }

    public function getCodeClient(): ?string
    {
        return $this->CodeClient;
    }

    public function setCodeClient(?string $CodeClient): self
    {
        $this->CodeClient = $CodeClient;

        return $this;
    }
}
