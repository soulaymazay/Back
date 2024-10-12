<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Ignore;
use App\Repository\LesmoyensRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Chauff;

#[ORM\Entity(repositoryClass: LesmoyensRepository::class)]
class Lesmoyens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    private ?string $annee = null;


    #[ORM\Column(type:"string")]
    private  $etat ;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $model = null;
/** @Ignore() */

    #[ORM\ManyToOne(inversedBy: 'moyens')]
    private ?Chauff $chauff = null;

    #[ORM\Column(length: 255)]
    private ?string $imagefilename = "";
    private string $image="";


    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
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

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

        return $this;
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

    /**
     * @return Collection<int, chauff>
     */
    

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getChauff(): ?Chauff
    {
        return $this->chauff;
    }

    public function setChauff(?Chauff $chauff): self
    {
        $this->chauff = $chauff;

        return $this;
    }


    public function getImageFilename(): ?string
    {
        return $this->imagefilename;
    }

    public function setImageFilename(string $imagefilename): self
    {
        $this->imagefilename = $imagefilename;

        return $this;
    }
    

}
