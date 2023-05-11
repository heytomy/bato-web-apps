<?php

namespace App\Entity;

use App\Repository\StatutChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutChantierRepository::class)]
#[ORM\Table(name: 'Apps_Statut')]
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

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: Appels::class)]
    private Collection $appels;

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: DevisARealiser::class)]
    private Collection $devisARealisers;

    public function __construct()
    {
        $this->chantierApps = new ArrayCollection();
        $this->appels = new ArrayCollection();
        $this->devisARealisers = new ArrayCollection();
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

    /**
     * @return Collection<int, Appels>
     */
    public function getAppels(): Collection
    {
        return $this->appels;
    }

    public function addAppel(Appels $appel): self
    {
        if (!$this->appels->contains($appel)) {
            $this->appels->add($appel);
            $appel->setStatut($this);
        }

        return $this;
    }

    public function removeAppel(Appels $appel): self
    {
        if ($this->appels->removeElement($appel)) {
            // set the owning side to null (unless already changed)
            if ($appel->getStatut() === $this) {
                $appel->setStatut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DevisARealiser>
     */
    public function getDevisARealisers(): Collection
    {
        return $this->devisARealisers;
    }

    public function addDevisARealiser(DevisARealiser $devisARealiser): self
    {
        if (!$this->devisARealisers->contains($devisARealiser)) {
            $this->devisARealisers->add($devisARealiser);
            $devisARealiser->setStatut($this);
        }

        return $this;
    }

    public function removeDevisARealiser(DevisARealiser $devisARealiser): self
    {
        if ($this->devisARealisers->removeElement($devisARealiser)) {
            // set the owning side to null (unless already changed)
            if ($devisARealiser->getStatut() === $this) {
                $devisARealiser->setStatut(null);
            }
        }

        return $this;
    }
}
