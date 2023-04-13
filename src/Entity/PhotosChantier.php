<?php

namespace App\Entity;

use App\Repository\PhotosChantierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotosChantierRepository::class)]
class PhotosChantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdPhoto', length: 30)]
    private ?string $id = null;

    #[ORM\Column(name: 'URL_Photo', length: 255)]
    private ?string $URL_Photo = null;

    #[ORM\Column(name: 'Date_Photo', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date_Photo = null;

    #[ORM\ManyToOne(inversedBy: 'photosChantiers')]
    #[ORM\JoinColumn(name: 'CodeChantier', referencedColumnName: 'Id', nullable: false)]
    private ?ChantierApps $codeChantier = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getURLPhoto(): ?string
    {
        return $this->URL_Photo;
    }

    public function setURLPhoto(string $URL_Photo): self
    {
        $this->URL_Photo = $URL_Photo;

        return $this;
    }

    public function getDatePhoto(): ?\DateTimeInterface
    {
        return $this->Date_Photo;
    }

    public function setDatePhoto(\DateTimeInterface $Date_Photo): self
    {
        $this->Date_Photo = $Date_Photo;

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
}
