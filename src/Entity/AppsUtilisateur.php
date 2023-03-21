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
#[UniqueEntity(fields: ['Nom_utilisateur'], message: 'There is already an account with this Nom_utilisateur')]
class AppsUtilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Veuillez ajouter un nom d\'utilisateur')]
    #[ORM\Column(length: 50, unique: true,  nullable: true)]
    private ?string $Nom_utilisateur = null;

    /**
     * @var string The hashed password
     */
    #[Assert\Regex(
        pattern: "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/",
        message: "Le mot de passe doit comporter au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial"
        )]
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

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'codeClient', targetEntity: RepCommentairesSAV::class)]
    private Collection $repCommentairesSAVs;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->repCommentairesSAVs = new ArrayCollection();
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

    /**
     * @return Collection<int, RepCommentairesSAV>
     */
    public function getRepCommentairesSAVs(): Collection
    {
        return $this->repCommentairesSAVs;
    }

    public function addRepCommentairesSAV(RepCommentairesSAV $repCommentairesSAV): self
    {
        if (!$this->repCommentairesSAVs->contains($repCommentairesSAV)) {
            $this->repCommentairesSAVs->add($repCommentairesSAV);
            $repCommentairesSAV->setCodeClient($this);
        }

        return $this;
    }

    public function removeRepCommentairesSAV(RepCommentairesSAV $repCommentairesSAV): self
    {
        if ($this->repCommentairesSAVs->removeElement($repCommentairesSAV)) {
            // set the owning side to null (unless already changed)
            if ($repCommentairesSAV->getCodeClient() === $this) {
                $repCommentairesSAV->setCodeClient(null);
            }
        }

        return $this;
    }
}
