<?php

declare(strict_types=1);

namespace Service;

use Currency\Configuration\CurrencyConfiguration;
use Currency\DataProvider\CurrencyConfigDataProvider;
use Currency\Entity\Rate;
use Currency\Enum\Code;
use Currency\Mapper\CurrencyMapper;
use Currency\Repository\EntityRepository;
use Currency\Repository\RepositoryInterface;
use Currency\Service\Conversion\AdaptiveConversionService;
use Currency\Service\Conversion\ConversionContext;
use Currency\Service\CurrencyService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class AdaptiveConversionServiceTest extends TestCase
{
    private CurrencyService $currencyService;

    public function setUp(): void
    {
        $dataProvider = new CurrencyConfigDataProvider(
            new Processor(),
            new CurrencyConfiguration(),
            new CurrencyMapper()
        );

        $currencyRepository = new EntityRepository(
            $dataProvider
        );

        $this->currencyService = new CurrencyService(
            $currencyRepository,
            $this->createMock(RepositoryInterface::class)
        );
        unset($dataProvider, $currencyRepository);
    }

    public function tearDown(): void
    {
        unset($this->currencyService);
    }


    /** @dataProvider rateDataProvider */
    public function testConvert(
        Rate $sourceRate,
        Rate $targetRate,
        float $amount,
        float $convertedAmount
    ): void {
        $convertor = new AdaptiveConversionService();

        $sourceCurrency = $this->currencyService->getByCodes($sourceRate->currencyCode)->current();
        $targetCurrency = $this->currencyService->getByCodes($targetRate->currencyCode)->current();

        $conversionContext = new ConversionContext(
            $amount,
            $sourceRate,
            $targetRate,
            $sourceCurrency,
            $targetCurrency,
        );

        $this->assertEquals($convertedAmount, $convertor->convert($conversionContext));
    }

    public static function rateDataProvider(): \Traversable
    {
        yield 'convert uah to usd' =>
        [
            new Rate(Code::UAH, new \DateTimeImmutable(), 1, 1,1),
            new Rate(Code::USD, new \DateTimeImmutable(), 35, 37,36),
            37,
            1
        ];

        yield 'convert eur to usd' =>
        [
            new Rate(Code::EUR, new \DateTimeImmutable(), 38, 39,40),
            new Rate(Code::USD, new \DateTimeImmutable(), 35, 37,36),
            1,
            1.03
        ];

        yield 'convert usd to eur' =>
        [
            new Rate(Code::USD, new \DateTimeImmutable(), 35, 37,36),
            new Rate(Code::EUR, new \DateTimeImmutable(), 38, 39,40),
            1,
            0.9
        ];

        yield 'convert uah to usd mid rate' =>
        [
            new Rate(Code::UAH, new \DateTimeImmutable(), 1, 1,1),
            new Rate(Code::USD, new \DateTimeImmutable(), null, null,36),
            36,
            1
        ];
    }
}
