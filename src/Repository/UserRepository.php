<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function delete(User $user)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }

    public function findAll(): array
    {
        $users = parent::findAll();
        asort($users);

        return $users;
    }
}
