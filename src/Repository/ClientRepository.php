<?php

namespace App\Repository;


use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ClientRepository extends ServiceEntityRepository

{ private $manager;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Client::class);
        $this->manager = $manager;
    }

    public function transformAll()
    {
        $Client = $this->findAll();
        $ClientArray = [];

        foreach ($Client as $client) {
            $clientArray[] = $this->transform($client);
        }

        return $clientArray;
    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */


    public function transform(Client $Client)
    {
        return $Client;
    }
    public function updateClient(Client $Client): Client
    {
        $this->manager->persist($Client);
        $this->manager->flush();
        return $Client;
    }

    public function save(Client $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Client $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */

}
