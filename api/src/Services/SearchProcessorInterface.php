<?php

declare(strict_types=1);

namespace App\Services;

interface SearchProcessorInterface
{
    /**
     * @param array $searchCriteria
     *
     * @return array
     */
    public function getResult(array $searchCriteria): array;
}
