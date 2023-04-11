<?php

namespace App\Entity;

use App\Repository\PhotosAppelsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotosAppelsRepository::class)]
class PhotosAppels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdPhoto', length: 30)]
    private ?string $id = null;

    #[ORM\Column(name: 'URL_Photo', length: 255, nullable: true)]
    private ?string $URL_Photo = null;

    #[ORM\Column(name: 'Date_Photo', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_Photo = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(name: 'id_appels', referencedColumnName: "id", nullable: false)]
    private ?Appels $idAppel = null;

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

    public function getIdAppel(): ?Appels
    {
        return $this->idAppel;
    }

    public function setIdAppel(?Appels $idAppel): self
    {
        $this->idAppel = $idAppel;

        return $this;
    }
}
