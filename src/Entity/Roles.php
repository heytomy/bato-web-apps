<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, name: "libelle")]
    private ?string $Libelle = null;

    #[ORM\ManyToMany(targetEntity: AppsUtilisateur::class, mappedBy: 'roles')]
    private Collection $appsUtilisateurs;

    public function __construct()
    {
        $this->appsUtilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    /**
     * @return Collection<int, AppsUtilisateur>
     */
    public function getAppsUtilisateurs(): Collection
    {
        return $this->appsUtilisateurs;
    }

    public function addAppsUtilisateur(AppsUtilisateur $appsUtilisateur): self
    {
        if (!$this->appsUtilisateurs->contains($appsUtilisateur)) {
            $this->appsUtilisateurs->add($appsUtilisateur);
            $appsUtilisateur->addRole($this);
        }

        return $this;
    }

    public function removeAppsUtilisateur(AppsUtilisateur $appsUtilisateur): self
    {
        if ($this->appsUtilisateurs->removeElement($appsUtilisateur)) {
            $appsUtilisateur->removeRole($this);
        }

        return $this;
    }
}
