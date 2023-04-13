<?php

namespace App\Entity;

use App\Repository\StatutChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutChantierRepository::class)]
class StatutChantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Id')]
    private ?int $id = null;

    #[ORM\Column(name: 'Statut', length: 50, nullable: true)]
    private ?string $statut = null;

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: ChantierApps::class)]
    private Collection $chantierApps;

    public function __construct()
    {
        $this->chantierApps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, ChantierApps>
     */
    public function getChantierApps(): Collection
    {
        return $this->chantierApps;
    }

    public function addChantierApp(ChantierApps $chantierApp): self
    {
        if (!$this->chantierApps->contains($chantierApp)) {
            $this->chantierApps->add($chantierApp);
            $chantierApp->setStatut($this);
        }

        return $this;
    }

    public function removeChantierApp(ChantierApps $chantierApp): self
    {
        if ($this->chantierApps->removeElement($chantierApp)) {
            // set the owning side to null (unless already changed)
            if ($chantierApp->getStatut() === $this) {
                $chantierApp->setStatut(null);
            }
        }

        return $this;
    }
}
