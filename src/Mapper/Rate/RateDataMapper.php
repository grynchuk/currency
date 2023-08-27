<?php

declare(strict_types=1);

namespace Currency\Mapper\Rate;

use Currency\Entity\EntityInterface;
use Currency\Entity\Rate;
use Currency\Enum\Code;
use Currency\Mapper\MapperInterface;

class RateDataMapper implements MapperInterface
{
    private const PROPERTY_DATE = 'date';

    private const PROPERTY_SELL = 'sell';

    private const PROPERTY_BUY = 'buy';

    private const PROPERTY_MID = 'mid';

    private const PROPERTY_CODE = 'code';

    private const PROPERTY_DATE_FORMAT = 'd-m-Y';

    public function toEntity(array $data): EntityInterface
    {
        if (
            false === array_key_exists(self::PROPERTY_DATE, $data)
            || false === array_key_exists(self::PROPERTY_BUY, $data)
            || false === array_key_exists(self::PROPERTY_SELL, $data)
            || false === array_key_exists(self::PROPERTY_MID, $data)
            || false === array_key_exists(self::PROPERTY_CODE, $data)
        ) {
            throw new \RuntimeException('invalid rate');
        }

        $date = $data[self::PROPERTY_DATE] instanceof \DateTimeInterface
            ? $data[self::PROPERTY_DATE]
            : \DateTimeImmutable::createFromFormat(self::PROPERTY_DATE_FORMAT, $data[self::PROPERTY_DATE]);

        return new Rate(
            $data[self::PROPERTY_CODE] instanceof Code
                ? $data[self::PROPERTY_CODE]
                : Code::tryFrom($data[self::PROPERTY_CODE]),
            $date,
            $data[self::PROPERTY_SELL],
            $data[self::PROPERTY_BUY],
            $data[self::PROPERTY_MID],
        );

    }

    public function toRaw(EntityInterface $entity): array
    {
        if (false === $entity instanceof Rate) {
            throw new \RuntimeException('Invalid Entity should be rate');
        }

        return [
          self::PROPERTY_MID => $entity->mid,
          self::PROPERTY_BUY => $entity->buy,
          self::PROPERTY_SELL => $entity->sell,
          self::PROPERTY_DATE => $entity->date->format(self::PROPERTY_DATE_FORMAT),
          self::PROPERTY_CODE => $entity->currencyCode->value,
        ];
    }
}
