<?php

namespace App\Entity;

use App\Repository\AppsUtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AppsUtilisateurRepository::class)]
class AppsUtilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true,  nullable: true)]
    private ?string $Nom_utilisateur = null;

    // #[ORM\Column]
    // private array $roles = [];

    /**
     * @var string The hashed password
     */
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

    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
     * 
     * 
     * @return Collection<int, WorkCategory>
     */
    
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getRolesCollection(): Collection
    {
        return $this->roles;
        // $roles = $this->roles;
        // // guarantee every user at least has ROLE_USER
        // $rolesTable[] = $roles


        // return array_unique($roles);
    }

    // public function setRoles(array $roles): self
    // {
    //     // $this->roles = $roles;

    //     return $this;
    // }

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

    /**
     * Cette fonction a été modifié pour qu'elle soit conforme avec la configuration de la base de donnée
     * Elle cherche si le rôle exist déjà, sinon elle le rajoute dans la liste des rôle
     */
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
    // public function removeRole(Roles $role): self
    // {
    //     if ($this->roles->removeElement($role)) {
    //         $roles->removeAppsUtilisateur($this);
    //     }

    //     return $this;
    // }
}
