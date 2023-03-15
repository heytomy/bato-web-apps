<?php

namespace App\Entity;

class savSearch
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