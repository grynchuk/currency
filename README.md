# Currency
Provide the minimum functionality to get rate and perform currency amount conversion (using php math). 

## Install
To install run:
```bash
composer require grynchuk/currency 
```

## Configuration

You may provide a configuration with available currencies that are used to get rates. Do do this you will need to add yaml config and pass it to `Currency\DataProvider\CurrencyConfigDataProvider`.
This yaml config should  looks like this:
```yaml
currency:
  items:
    - { code: !php/enum Currency\Enum\Code::USD, precision: 2, description: 'United States Dollar'}
```
By default this configuration contain only three currency `USD` `EUR` `PLN`.

As for now no bridge to Symfony is provided (may be I\`ll implement it later), so you need to do it by your self, to get more details see tests.
Basic Symfony DI config (`symfony/dependency-injection`) to get rates from Monobank looks like this:

```yaml
services:
  app.currency.remote.service:
    class: Currency\Service\CurrencyService
    arguments:
      - '@app.currency.repository'
      - '@app.currency.rate.repository'

  app.currency.rate.repository:
    class: Currency\Repository\EntityRepository
    arguments:
      - '@app.currency.rate.provider'


  app.currency.rate.provider:
    class: Currency\DataProvider\Rate\MonobankRateDataProvider
    arguments:
      - '@app.currency.rate.client.monobank'
      - !php/enum Currency\Enum\Code::UAH
      - '@app.currency.rate.dta_mapper.monobank'

  app.currency.rate.dta_mapper.monobank:
    class: Currency\Mapper\Rate\RateDataMapper

  app.currency.rate.client.monobank:
    class: Monobank\MonobankClient
    arguments:
      - '@app.currency.rate.client.guzzle'
      - '@app.currency.rate.client.monobank.empty_token'

  app.currency.rate.client.monobank.empty_token:
    class: Monobank\ValueObject\Token
    arguments:
      - 'empty_token'

  app.currency.rate.client.guzzle:
    class: GuzzleHttp\Client

  app.currency.repository:
    class: Currency\Repository\EntityRepository
    arguments:
      - '@app.currency.data_provider'

  app.currency.data_provider:
    class: Currency\DataProvider\CurrencyConfigDataProvider
    arguments:
      - '@app.currency.config.processor'
      - '@app.currency.config.definition'
      - '@app.currency.mapper'

  app.currency.config.processor:
    class: Symfony\Component\Config\Definition\Processor

  app.currency.config.definition:
    class: Currency\Configuration\CurrencyConfiguration

  app.currency.mapper:
    class: Currency\Mapper\CurrencyMapper
```

## How to use

To use core functional you need to init one of the service form `Currency\Service` namespace and then use it according to provided interface.


