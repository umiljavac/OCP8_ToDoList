<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 24/05/2018
 * Time: 11:15
 */

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function tasksToDo($user)
    {
        return $this->createQueryBuilder('t')
           ->andWhere('t.author = :user OR t.author is null')
           ->setParameter('user', $user)
           ->andWhere('t.isDone = false')
           ->getQuery()
           ->execute()
        ;
    }

    public function tasksDone($user)
    {
        return $this->createQueryBuilder('t')
           ->andWhere('t.author = :user OR t.author is null')
           ->setParameter('user', $user)
           ->andWhere('t.isDone = true')
           ->getQuery()
           ->execute()
        ;
    }
}
