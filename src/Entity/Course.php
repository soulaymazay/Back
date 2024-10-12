<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ApiResource]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?int $client = null;

    #[ORM\Column(length: 255)]
    private ?int $Chauffeur = null;

    #[ORM\Column(length: 255)]
    private ?string $destinationGPS = null;

    #[ORM\Column(length: 255)]
    private ?string $positionGPS = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chauffeurGPS = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $avis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $inputposition = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $inputdestination = null;
    #[ORM\Column(length: 255)]
    private ?int $moyen = null;

    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $StartDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $FinishDateTime = null;

    public string $chauffeurName;
    public string $clientName;
    public string $moyenName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): int
    {
        return $this->client;
    }

    public function setClient(int $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getChauffeur(): int
    {
        return $this->Chauffeur;
    }

    public function setChauffeur(int $Chauffeur): self
    {
        $this->Chauffeur = $Chauffeur;

        return $this;
    }

    public function getDestinationGPS(): ?string
    {
        return $this->destinationGPS;
    }

    public function setDestinationGPS(string $destinationGPS): self
    {
        $this->destinationGPS = $destinationGPS;

        return $this;
    }

    public function getPositionGPS(): ?string
    {
        return $this->positionGPS;
    }

    public function setPositionGPS(string $positionGPS): self
    {
        $this->positionGPS = $positionGPS;

        return $this;
    }

    public function getChauffeurGPS(): ?string
    {
        return $this->chauffeurGPS;
    }

    public function setChauffeurGPS(string $chauffeurGPS): self
    {
        $this->chauffeurGPS = $chauffeurGPS;

        return $this;
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

    public function getAvis(): ?string
    {
        return $this->avis;
    }

    public function setAvis(?string $avis): self
    {
        $this->avis = $avis;

        return $this;
    }

    public function getInputposition(): ?string
    {
        return $this->inputposition;
    }

    public function setInputposition(?string $inputposition): self
    {
        $this->inputposition = $inputposition;

        return $this;
    }

    public function getInputdestination(): ?string
    {
        return $this->inputdestination;
    }

    public function setInputdestination(?string $inputdestination): self
    {
        $this->inputdestination = $inputdestination;

        return $this;
    }

    public function getMoyen(): int
    {
        return $this->moyen;
    }

    public function setMoyen(int $moyen): self
    {
        $this->moyen = $moyen;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->StartDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $StartDateTime): self
    {
        $this->StartDateTime = $StartDateTime;

        return $this;
    }

    public function getFinishDateTime(): ?\DateTimeInterface
    {
        return $this->FinishDateTime;
    }

    public function setFinishDateTime(?\DateTimeInterface $FinishDateTime): self
    {
        $this->FinishDateTime = $FinishDateTime;

        return $this;
    }

}