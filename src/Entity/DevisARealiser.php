<?php

namespace App\Entity;

use App\Repository\DevisARealiserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisARealiserRepository::class)]
#[ORM\Table(name: 'Devis_a_realiser')]
class DevisARealiser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Id')]
    private ?int $id = null;

    #[ORM\Column(name: 'Nom', length: 50)]
    private ?string $nom = null;

    #[ORM\Column(name: 'Prenom', length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(name: 'Adr', length: 100)]
    private ?string $adr = null;

    #[ORM\Column(name: 'CP', length: 10)]
    private ?string $cp = null;

    #[ORM\Column(name: 'Ville', length: 100)]
    private ?string $ville = null;

    #[ORM\Column(name: 'Tel', length: 50)]
    private ?string $tel = null;

    #[ORM\Column(name: 'Mail', length: 255, nullable: true)]
    private ?string $mail = null;

    #[ORM\ManyToOne(inversedBy: 'devisARealisers')]
    #[ORM\JoinColumn(nullable: true, name: "CodeClient", referencedColumnName: "Code")]
    private ?ClientDef $codeClient = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'codeDevis', targetEntity: PhotosDevis::class, orphanRemoval: true)]
    private Collection $photosDevis;

    public function __construct()
    {
        $this->photosDevis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdr(): ?string
    {
        return $this->adr;
    }

    public function setAdr(string $adr): self
    {
        $this->adr = $adr;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getCodeClient(): ?ClientDef
    {
        return $this->codeClient;
    }

    public function setCodeClient(?ClientDef $codeClient): self
    {
        $this->codeClient = $codeClient;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, PhotosDevis>
     */
    public function getPhotosDevis(): Collection
    {
        return $this->photosDevis;
    }

    public function addPhotosDevi(PhotosDevis $photosDevi): self
    {
        if (!$this->photosDevis->contains($photosDevi)) {
            $this->photosDevis->add($photosDevi);
            $photosDevi->setCodeDevis($this);
        }

        return $this;
    }

    public function removePhotosDevi(PhotosDevis $photosDevi): self
    {
        if ($this->photosDevis->removeElement($photosDevi)) {
            // set the owning side to null (unless already changed)
            if ($photosDevi->getCodeDevis() === $this) {
                $photosDevi->setCodeDevis(null);
            }
        }

        return $this;
    }
}
