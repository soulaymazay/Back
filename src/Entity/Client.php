<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[UniqueEntity(fields: ['nom'], message: 'There is already an account with this nom')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id ;

   

    #[ORM\Column(type:"string")]
    private  $etat ;

    #[ORM\OneToOne(cascade: ['remove'])]
    private ?User $user = null;



    // #[ORM\Column(type: 'boolean')]
    // private $isVerified = false;

    public function getUser() {
        return $this->user;
    }

    public function setUser(User $userToSet) {
        $this->user=$userToSet;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function __construct()
    {
    }
    public function getEtat()
    {
        return $this->etat;
    }
    public function setEtat(string $etat): void
    {
        $this->etat = $etat;
    }

    // public function isVerified(): bool
    // {
    //     return $this->isVerified;
    // }

    // public function setIsVerified(bool $isVerified): self
    // {
    //     $this->isVerified = $isVerified;

    //     return $this;
    // }

  

    

       
    
}
