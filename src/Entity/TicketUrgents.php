<?php

namespace App\Entity;

use App\Repository\TicketUrgentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketUrgentsRepository::class)]
class TicketUrgents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ticketUrgents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appels $AppelsUrgents = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppelsUrgents(): ?Appels
    {
        return $this->AppelsUrgents;
    }

    public function setAppelsUrgents(?Appels $AppelsUrgents): self
    {
        $this->AppelsUrgents = $AppelsUrgents;

        return $this;
    }
}
