<?php

declare(strict_types=1);

namespace Currency\Repository;

use Currency\DataProvider\DataProviderInterface;
use Currency\Repository\SearchCondition\SearchConditionInterface;

class EntityRepository implements RepositoryInterface
{
    public function __construct(
        public readonly DataProviderInterface $dataProvider
    ) {
    }

    public function getBy(SearchConditionInterface $searchCondition): \Traversable
    {
        foreach ($this->dataProvider->getData() as $entity) {

            if (true === $searchCondition->apply($entity)) {
                yield $entity;
            }
        }
    }
}
