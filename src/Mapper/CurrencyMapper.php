<?php

declare(strict_types=1);

namespace Currency\Mapper;

use Currency\Entity\Currecny;
use Currency\Entity\EntityInterface;
use Currency\Enum\Code;

class CurrencyMapper implements MapperInterface
{
    private const PROPERTY_CODE = 'code';

    private const PROPERTY_PRECISION = 'precision';

    private const PROPERTY_DESCRIPTION = 'description';

    public function toEntity(array $data): EntityInterface
    {
        if (
            false === array_key_exists(self::PROPERTY_CODE, $data)
            || false === array_key_exists(self::PROPERTY_PRECISION, $data)
            || false === array_key_exists(self::PROPERTY_DESCRIPTION, $data)
        ) {
            throw new \RuntimeException('Invalid currency raw data');
        }

        return new Currecny(
            $data[self::PROPERTY_CODE] instanceof Code
                ? $data[self::PROPERTY_CODE]
                : Code::tryFrom($data[self::PROPERTY_CODE]),
            $data[self::PROPERTY_PRECISION],
            $data[self::PROPERTY_DESCRIPTION],
        );
    }

    public function toRaw(EntityInterface $entity): array
    {
        if (false === $entity instanceof Currecny) {
            throw new \InvalidArgumentException('Currency class should be provided');
        }

        return [
            self::PROPERTY_DESCRIPTION => $entity->description,
            self::PROPERTY_PRECISION => $entity->precision,
            self::PROPERTY_CODE => $entity->code->value,
        ];
    }
}
