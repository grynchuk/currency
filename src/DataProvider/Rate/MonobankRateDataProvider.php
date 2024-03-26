<?php

declare(strict_types=1);

namespace Currency\DataProvider\Rate;

use Currency\DataProvider\DataProviderInterface;
use Currency\DataProvider\Generator;
use Currency\Entity\Currecny;
use Currency\Enum\Code;
use Currency\Mapper\MapperInterface;
use Monobank\MonobankClient;

class MonobankRateDataProvider implements DataProviderInterface
{
    private const PROPERTY_CURRENCY_BASE = "currencyCodeB";

    private const PROPERTY_CURRENCY = "currencyCodeA";

    private const PROPERTY_DATE = 'date';

    private const PROPERTY_SELL_RATE = 'rateSell';

    private const PROPERTY_BUY_RATE = 'rateBuy';

    private const PROPERTY_MID_RATE = 'rateCross';

    public function __construct(
        private readonly MonobankClient $monobankClient,
        private readonly Code $code,
        private readonly MapperInterface $mapper
    ) {
    }

    public function getData(): \Generator
    {
        $rawRateData = [];

        foreach ($this->monobankClient->getExchangeRates() as $rawMonoData) {
            $baseCurrencyNum = (string) $rawMonoData[self::PROPERTY_CURRENCY_BASE];
            if ($baseCurrencyNum !== $this->code->getNum()) {
                throw new \RuntimeException('Invalid base currency');
            }

            yield $this->mapper->toEntity([
              'sell' => (float) $rawMonoData[self::PROPERTY_SELL_RATE],
              'buy' => (float) $rawMonoData[self::PROPERTY_BUY_RATE],
              'mid' => (float) $rawMonoData[self::PROPERTY_MID_RATE],
              'date' => (new \DateTimeImmutable)->setTimestamp($rawMonoData[self::PROPERTY_DATE]),
              'code' => Code::tryFromNum((string) $rawMonoData[self::PROPERTY_CURRENCY])
            ]);
        }
    }
}
