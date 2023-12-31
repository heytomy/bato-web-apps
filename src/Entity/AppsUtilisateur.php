<?php

namespace App\Entity;

use App\Repository\AppsUtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppsUtilisateurRepository::class)]
#[UniqueEntity(fields: ['Nom_utilisateur'], message: 'Il existe déjà un compte avec ce nom d\'utilisateur')]
#[UniqueEntity(fields: ['colorCode'], message: 'Choisissez un code couleur qui n\'est pas déjà utilisé')]
class AppsUtilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Veuillez ajouter un nom d\'utilisateur')]
    #[ORM\Column(length: 50, unique: true,  nullable: true)]
    private ?string $Nom_utilisateur = null;

    #[ORM\Column(length: 255,  nullable: true)]
    private ?string $Mot_de_passe = null;

    #[ORM\ManyToOne(inversedBy: 'comptes')]
    #[ORM\JoinColumn(nullable: false, name: "ID_Utilisateur", referencedColumnName: "ID_Utilisateur")]
    private ?DefAppsUtilisateur $ID_Utilisateur = null;

    /**
     * La variable $rôles est une liste qui contient tous les rôles d'un utilisateur donné
     * Cette variable est déclarée comme une Entité pour qu'elle reste conforme avec la configuration de la base de donnée
     */
    #[ORM\ManyToMany(targetEntity: Roles::class, inversedBy: 'appsUtilisateurs')]
    private collection $roles;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isVerified = false;

    #[ORM\Column(name: 'colorCode',length: 7, nullable: true)]
    private ?string $colorCode = null;

    #[ORM\OneToMany(mappedBy: 'ID_Utilisateur', targetEntity: ChantierApps::class)]
    private Collection $chantierApps;

    #[ORM\OneToMany(mappedBy: 'ID_Utilisateur', targetEntity: Appels::class)]
    private Collection $appels;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->chantierApps = new ArrayCollection();
        $this->appels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->Nom_utilisateur;
    }

    public function setNomUtilisateur(string $Nom_utilisateur): self
    {
        $this->Nom_utilisateur = $Nom_utilisateur;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->Nom_utilisateur;
    }
   
    /**
     * @see UserInterface
     * Cette fonction a été générée avec la commande "make:user"
     * Elle a été changée pour qu'elle reste conforme avec la base de donnée
     * Cette fonction prend les libelles dans l'Entité Roles et les place dans une liste (array)
     * @return array
     */
    public function getRoles(): array
    {
        $rolesArray = [];
        $rolesArray[] = 'ROLE_USER';

        $rolesCollection = $this->roles;

        foreach ($rolesCollection as $key => $role) {
            $rolesArray[] = $role->getLibelle();
            }
        return array_unique($rolesArray);
    }

     /**
     * Cette fonction est la fonction générée par la relation entre les Entités Apps_Utilisateur et Roles
     * Donc c'est une collection
     * @return Collection
     */
    
    public function getRolesCollection(): Collection
    {
        return $this->roles;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->Mot_de_passe;
    }

    public function setPassword(string $Mot_de_passe): self
    {
        $this->Mot_de_passe = $Mot_de_passe;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getIDUtilisateur(): ?DefAppsUtilisateur
    {
        return $this->ID_Utilisateur;
    }

    public function setIDUtilisateur(?DefAppsUtilisateur $ID_Utilisateur): self
    {
        $this->ID_Utilisateur = $ID_Utilisateur;

        return $this;
    }


    public function addRole(Roles $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }
    
    /**
     * Cette fonction a été modifié pour qu'elle soit conforme avec la configuration de la base de donnée
     * Elle cherche si le rôle exist déjà, sinon elle le rajoute dans la liste des rôle
     */
    public function removeRole(Roles $role): self
    {
        if ($this->roles->removeElement($role)) {
            $role->removeAppsUtilisateur($this);
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getColorCode(): ?string
    {
        return $this->colorCode;
    }

    public function setColorCode(?string $colorCode): self
    {
        $this->colorCode = $colorCode;

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
            $chantierApp->setIDUtilisateur($this);
        }

        return $this;
    }

    public function removeChantierApp(ChantierApps $chantierApp): self
    {
        if ($this->chantierApps->removeElement($chantierApp)) {
            // set the owning side to null (unless already changed)
            if ($chantierApp->getIDUtilisateur() === $this) {
                $chantierApp->setIDUtilisateur(null);
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
            $appel->setIDUtilisateur($this);
        }

        return $this;
    }

    public function removeAppel(Appels $appel): self
    {
        if ($this->appels->removeElement($appel)) {
            // set the owning side to null (unless already changed)
            if ($appel->getIDUtilisateur() === $this) {
                $appel->setIDUtilisateur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->ID_Utilisateur;
    }
}
