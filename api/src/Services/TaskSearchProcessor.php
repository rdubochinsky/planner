<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Task;
use App\Form\TaskSearchType;
use App\Repository\TaskRepository;
use Doctrine\ORM\QueryBuilder;

class TaskSearchProcessor implements SearchProcessorInterface
{
    private TaskRepository $taskRepository;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param array $searchCriteria
     *
     * @return Task[]
     */
    public function getResult(array $searchCriteria): array
    {
        $qb = $this->taskRepository->createQueryBuilder('task');

        $qb->addSelect('taskLabel')
            ->leftJoin('task.labels', 'taskLabel');

        $this->addWhere($qb, $searchCriteria);

        $qb->addOrderBy('task.id', 'desc');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $qb
     * @param array $searchCriteria
     */
    private function addWhere(QueryBuilder $qb, array $searchCriteria): void
    {
        $ex = $qb->expr();

        foreach ($searchCriteria as $key => $value) {
            switch ($key) {
                case TaskSearchType::NAME_FIELD:
                    $qb->andWhere($ex->like('task.name', ':name'))
                        ->setParameter('name', sprintf('%%%s%%', $value));
                    break;

                case TaskSearchType::LABELS_FIELD:
                    $qb->innerJoin('task.labels', 'searchableTaskLabel')
                        ->andWhere($ex->in('searchableTaskLabel.id', ':labelIds'))
                        ->setParameter('labelIds', $value);
                    break;
            }
        }
    }
}
