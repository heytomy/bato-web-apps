<?php

namespace App\Entity;

use App\Repository\ClientDefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientDefRepository::class)]
#[ORM\Table(name:"ClientDef")]
class ClientDef
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Code", length: 8)]
    private ?string $id = null;

    #[ORM\Column(length: 30)]
    private ?string $Nom = null;

    #[ORM\Column(length: 50)]
    private ?string $Adr = null;

    #[ORM\Column(length: 15)]
    private ?string $CP = null;

    #[ORM\Column(length: 25)]
    private ?string $Ville = null;

    #[ORM\Column(length: 30)]
    private ?string $Tel = null;

    #[ORM\Column(name:"EMail",length: 50)]
    private ?string $EMail = null;

    #[ORM\OneToMany(mappedBy: 'CodeClient', targetEntity: Contrat::class)]
    private Collection $contrats;

    #[ORM\OneToMany(mappedBy: 'CodeClient', targetEntity: Appels::class)]
    private Collection $appels;

    #[ORM\OneToMany(mappedBy: 'codeClient', targetEntity: DevisARealiser::class)]
    private Collection $devisARealisers;

    #[ORM\OneToMany(mappedBy: 'codeClient', targetEntity: ChantierApps::class)]
    private Collection $chantierApps;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
        $this->appels = new ArrayCollection();
        $this->devisARealisers = new ArrayCollection();
        $this->chantierApps = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getAdr(): ?string
    {
        return $this->Adr;
    }

    public function setAdr(string $Adr): self
    {
        $this->Adr = $Adr;

        return $this;
    }

    public function getCP(): ?string
    {
        return $this->CP;
    }

    public function setCP(string $CP): self
    {
        $this->CP = $CP;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getEMail(): ?string
    {
        return $this->EMail;
    }

    public function setEMail(string $EMail): self
    {
        $this->EMail = $EMail;

        return $this;
    }

    /**
     * @return Collection<int, Contrat>
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): self
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setCodeClient($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): self
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getCodeClient() === $this) {
                $contrat->setCodeClient(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNom();
        
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
            $appel->setCodeClient($this);
        }

        return $this;
    }

    public function removeAppel(Appels $appel): self
    {
        if ($this->appels->removeElement($appel)) {
            // set the owning side to null (unless already changed)
            if ($appel->getCodeClient() === $this) {
                $appel->setCodeClient(null);
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
            $devisARealiser->setCodeClient($this);
        }

        return $this;
    }

    public function removeDevisARealiser(DevisARealiser $devisARealiser): self
    {
        if ($this->devisARealisers->removeElement($devisARealiser)) {
            // set the owning side to null (unless already changed)
            if ($devisARealiser->getCodeClient() === $this) {
                $devisARealiser->setCodeClient(null);
            }
        }

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
            $chantierApp->setCodeClient($this);
        }

        return $this;
    }

    public function removeChantierApp(ChantierApps $chantierApp): self
    {
        if ($this->chantierApps->removeElement($chantierApp)) {
            // set the owning side to null (unless already changed)
            if ($chantierApp->getCodeClient() === $this) {
                $chantierApp->setCodeClient(null);
            }
        }

        return $this;
    }
}
