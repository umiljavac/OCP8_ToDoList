<?php
/**
 * This file is a part of the ToDoList project of Openclassrooms PHP/Symfony
 * development course.
 *
 * (c) Sarah Khalil
 * (c) Ulrich Miljavac
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TaskRepository
 */
class TaskRepository extends ServiceEntityRepository
{
    /**
     * TaskRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function tasksToDo(User $user)
    {
        return $this->createQueryBuilder('t')
           ->andWhere('t.author = :user OR t.author is null')
           ->setParameter('user', $user)
           ->andWhere('t.isDone = false')
           ->getQuery()
           ->execute()
        ;
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function tasksDone(User $user)
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
