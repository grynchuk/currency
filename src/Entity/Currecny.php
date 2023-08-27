<?php

declare(strict_types=1);

namespace Currency\Entity;

use Currency\Enum\Code;

final class Currecny implements EntityInterface
{
    public function __construct(
        public readonly Code $code,
        public readonly int $precision,
        public readonly string $description,
    ) {
    }
}
