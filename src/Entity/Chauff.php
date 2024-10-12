<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Ignore;
use App\Repository\ChauffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Lesmoyens;
use App\Entity\Clients;


#[ORM\Entity(repositoryClass: ChauffRepository::class)]
class Chauff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numpermis = null;


    #[ORM\Column(type:"string")]
    private $etat;


    #[ORM\OneToOne(cascade: [ 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $etatcompte = null;




    public function setUser(User $userToSet) {
        $this->user=$userToSet;
    }
    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumpermis(): ?string
    {
        return $this->numpermis;
    }

    public function setNumpermis(string $numpermis): self
    {
        $this->numpermis = $numpermis;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->user->getUsername();
    }



    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getEtatcompte(): ?string
    {
        return $this->etatcompte;
    }

    public function setEtatcompte(string $etatcompte): self
    {
        $this->etatcompte = $etatcompte;

        return $this;
    }



}
