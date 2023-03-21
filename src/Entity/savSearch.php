<?php

namespace App\Entity;

class SAVSearch
{
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom = null): self
    {
        $this->nom = $nom;

        return $this;
    }
}