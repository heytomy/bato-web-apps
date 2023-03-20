<?php

namespace App\Entity;

use App\Repository\TicketUrgentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketUrgentsRepository::class)]
class TicketUrgents
{
    const low      = 0;
    const medium   = 1;
    const high     = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ticketUrgents')]
    #[ORM\JoinColumn(referencedColumnName:'id', nullable: true)]
    private ?Appels $AppelsUrgents = null;

    #[ORM\ManyToOne(inversedBy: 'ticketUrgents')]
    #[ORM\JoinColumn(referencedColumnName:'id', nullable: true)]
    private ?AppelsSAV $AppelsSAV = null;

    #[ORM\Column]
    private ?int $status = null;


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

    public function getAppelsSAV(): ?AppelsSAV
    {
        return $this->AppelsSAV;
    }

    public function setAppelsSAV(?AppelsSAV $AppelsSAV): self
    {
        $this->AppelsSAV = $AppelsSAV;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $allowedStatus = [
            self::low,
            self::medium,
            self::high,
        ];
      
        if (!in_array($status, $allowedStatus)) {
            throw new \InvalidArgumentException('Invalid status');
        }
        
        $this->status = $status;

        return $this;
    }

}
