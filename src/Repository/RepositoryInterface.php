<?php

declare(strict_types=1);

namespace Currency\Repository;

use Currency\Repository\SearchCondition\SearchConditionInterface;

interface RepositoryInterface
{
    public function getBy(SearchConditionInterface $searchCondition): \Traversable;
}
