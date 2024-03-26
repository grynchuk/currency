<?php

declare(strict_types=1);

namespace Test\Currency\Service;

use Currency\Configuration\CurrencyConfiguration;
use Currency\DataProvider\CurrencyConfigDataProvider;
use Currency\DataProvider\Rate\MonobankRateDataProvider;
use Currency\Enum\Code;
use Currency\Mapper\CurrencyMapper;
use Currency\Mapper\Rate\RateDataMapper;
use Currency\Repository\EntityRepository;
use Currency\Repository\RepositoryInterface;
use Currency\Service\CurrencyService;
use Currency\Service\CurrencyServiceInterface;
use GuzzleHttp\Client;
use Monobank\MonobankClient;
use Monobank\ValueObject\Token;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class CurrencyServiceTest extends TestCase
{

    private EntityRepository $currencyRepository;

    public function setUp(): void
    {
        $dataProvider = new CurrencyConfigDataProvider(
            new Processor(),
            new CurrencyConfiguration(),
            new CurrencyMapper()
        );
        $this->currencyRepository = new EntityRepository(
            $dataProvider,
        );
        unset($dataProvider);
        parent::setUp();
    }

    public function tearDown(): void
    {
        unset($this->currencyRepository);
        parent::tearDown();
    }

    public function testGetByCodes(): void
    {
        $currencyService = new CurrencyService(
            $this->currencyRepository,
            $this->createMock(RepositoryInterface::class)
        );

        $currency = $currencyService->getByCodes(Code::UAH)->current();

        $this->assertSame($currency->code, Code::UAH);
        $this->assertSame($currency->precision, 2);
        $this->assertSame($currency->description, 'Ukrainian Hryvna');
    }

    public function testGetMonoRate(): void
    {
        $monoRepository = new EntityRepository(
            new MonobankRateDataProvider($this->getMonoBankClient(), Code::UAH, new RateDataMapper()),
        );
        $currencyService = new CurrencyService(
            $this->currencyRepository,
            $monoRepository
        );

        $rate = $currencyService->getCurrentRate(Code::USD)->current();
        $this->assertSame(36.65, $rate->buy);
    }

    private function getMonoBankClient(): MonobankClient
    {
        $rates = [
            [
                "currencyCodeA"=> 840,
                "currencyCodeB" => 980,
                "date" => 1693688473,
                "rateBuy" => 36.65,
                "rateCross" => 0,
                "rateSell" => 37.4406,
            ]
        ];

        $client = $this->createMock(MonobankClient::class);
        $client->expects($this->once())
            ->method('getExchangeRates')
            ->willReturn(
                $rates
            );

        return $client;
    }
}
