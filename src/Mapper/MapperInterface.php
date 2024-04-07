<?php

declare(strict_types=1);

namespace Currency\Mapper;

use Currency\Entity\EntityInterface;

interface MapperInterface
{
    public function toEntity(array $data): EntityInterface;

    public function toRaw(EntityInterface $entity): array;
}
