<?php

namespace App\Repository;

use App\Entity\Lesmoyens;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class LesmoyensRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Lesmoyens::class);
        $this->manager = $manager;
    }

    public function transformAll()
    {
        $lesmoyens = $this->findAll();
        $lesmoyensArray = [];

        foreach ($lesmoyens as $lesmoyen) {
            $lesmoyensArray[] = $this->transform($lesmoyen);
        }

        return $lesmoyensArray;
    }

    public function transform(Lesmoyens $lesmoyens)
    {          
        $image=$lesmoyens->getImage();

        return [
            'id' => (integer)$lesmoyens->getId(),
            'nom' => (string)$lesmoyens->getNom(),
            'marque' => (string)$lesmoyens->getMarque(),
            'model' => (string)$lesmoyens->getModel(),
            'couleur' => (string)$lesmoyens->getCouleur(),
            'etat' => (string)$lesmoyens->getEtat(),
            'chauff_id' => (string)$lesmoyens->getChauff()->getId(),
            'image'=>$image
                   ];
    }

    public function updateLesmoyens(Lesmoyens $lesmoyens): Lesmoyens
    {
        $this->manager->persist($lesmoyens);
        $this->manager->flush();
        return $lesmoyens;
    }

    public function save(Lesmoyens $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Lesmoyens $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }
}
