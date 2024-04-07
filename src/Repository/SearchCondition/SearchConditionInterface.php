<?php

declare(strict_types=1);

namespace Currency\Repository\SearchCondition;

use Currency\Entity\EntityInterface;

interface SearchConditionInterface
{
    public function apply(EntityInterface $entity): bool;
}
