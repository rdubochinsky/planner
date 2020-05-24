<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param int $id
     *
     * @return Task|null
     */
    public function getOne(int $id): ?Task
    {
        $qb = $this->createQueryBuilder('task');

        return $qb
            ->addSelect('taskLabel')
            ->leftJoin('task.labels', 'taskLabel')
            ->where($qb->expr()->eq('task.id', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
