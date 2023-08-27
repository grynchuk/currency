<?php

declare(strict_types=1);

namespace Currency\Configuration;

use Currency\Enum\Code;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class CurrencyConfiguration implements ConfigurationInterface
{
    public const CONFIG_ITEMS = 'items';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('currency');

        $treeBuilder->getRootNode()
            ->children()
               ->arrayNode(self::CONFIG_ITEMS)
                  ->arrayPrototype()
                    ->children()
                       ->enumNode('code')
                          ->values(Code::cases())
                       ->end()
                       ->integerNode('precision')
                          ->min(0)
                          ->max(5)
                       ->end()
                       ->scalarNode('description')->end()
                    ->end()
                  ->end()
               ->end()
            ->end();

        return $treeBuilder;
    }
}
