<?php

declare(strict_types=1);

namespace Currency\Repository\SearchCondition\Rate;

use Currency\Entity\EntityInterface;
use Currency\Entity\Rate;
use Currency\Enum\Code;
use Currency\Repository\SearchCondition\SearchConditionInterface;

class CodeSearchCondition implements SearchConditionInterface
{
    public function __construct(
        private readonly Code $code
    ) {
    }

    public function apply(EntityInterface $entity): bool
    {
        if (false === $entity instanceof Rate) {
            throw  new \RuntimeException('Invalid entity to filter');
        }

        return $this->code === $entity->currencyCode;
    }
}
