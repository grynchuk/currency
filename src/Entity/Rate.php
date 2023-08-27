<?php

declare(strict_types=1);

namespace Currency\Entity;

use Currency\Enum\Code;

final class Rate implements EntityInterface
{
    public function __construct(
        public readonly Code $currencyCode,
        public readonly \DateTimeImmutable $date,
        public readonly ?float $sell,
        public readonly ?float $buy,
        public readonly ?float $mid,
    ) {
        if (false === $this->checkRate()) {
            throw new \RuntimeException('Invalid rate set');
        }
    }

    private function checkRate(): bool
    {
        return (null !== $this->sell && null !== $this->buy) || null !== $this->mid;
    }
}
