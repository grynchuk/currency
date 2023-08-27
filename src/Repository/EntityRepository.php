<?php

declare(strict_types=1);

namespace Currency\Repository;

use Currency\DataProvider\DataProviderInterface;
use Currency\Mapper\MapperInterface;
use Currency\Repository\SearchCondition\SearchConditionInterface;

class EntityRepository implements RepositoryInterface
{
    public function __construct(
        public readonly DataProviderInterface $dataProvider,
        public readonly MapperInterface $mapper
    ) {
    }

    public function getBy(SearchConditionInterface $searchCondition): \Traversable
    {
        foreach ($this->dataProvider->getData() as $rawData) {
            $currency = $this->mapper->toEntity($rawData);
            if (true === $searchCondition->apply($currency)) {
                yield $currency;
            }
        }
    }
}
