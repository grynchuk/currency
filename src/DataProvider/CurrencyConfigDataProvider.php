<?php

declare(strict_types=1);

namespace Currency\DataProvider;

use Currency\Configuration\CurrencyConfiguration;
use Currency\Mapper\MapperInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class CurrencyConfigDataProvider implements DataProviderInterface
{
    public function __construct(
        private readonly Processor $processor,
        private readonly ConfigurationInterface $configuration,
        private readonly MapperInterface $mapper,
        private readonly string $configPath = '',
    ) {
    }

    public function getData(): \Generator
    {
        $configPath = empty($this->configPath)
            ? __DIR__ . '/../../config/currency.yaml'
            : $this->configPath;

        if (false === file_exists($configPath)) {
            throw new \RuntimeException('Invalid currency configuration');
        }

        $config = $this->processor->processConfiguration(
            $this->configuration,
            Yaml::parse(file_get_contents($configPath), Yaml::PARSE_CONSTANT)
        );


        foreach ($config[CurrencyConfiguration::CONFIG_ITEMS] ?? [] as $item) {
            yield $this->mapper->toEntity($item);
        }
    }
}
