<?php

declare(strict_types=1);

namespace Currency\Repository\SearchCondition;

use Currency\Entity\EntityInterface;

class ComplexSearchCondition implements SearchConditionInterface
{
    /**
     * @var SearchConditionInterface[]
     */
    private array $searchConditions;


    public function __construct(
        private readonly Condition $condition,
        SearchConditionInterface ... $searchConditions
    ) {
        $this->searchConditions = $searchConditions;
    }

    public function apply(EntityInterface $entity): bool
    {
        return array_reduce(
            $this->searchConditions,
            fn ($carry, SearchConditionInterface $searchCondition) =>
            match ($this->condition) {
                Condition::AND => $carry && $searchCondition->apply($entity),
                Condition::OR => $carry || $searchCondition->apply($entity),
            },
            match ($this->condition) {
                Condition::AND => true,
                Condition::OR => false,
            }
        );
    }
}
