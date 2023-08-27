<?php

declare(strict_types=1);

namespace Currency\Repository\SearchCondition\Currency;

use Currency\Entity\Currecny;
use Currency\Entity\EntityInterface;
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
        if (false === $entity instanceof Currecny) {
            throw new \RuntimeException('invalid entity to filter');
        }

        return $this->code === $entity->code;
    }
}
