<?php

declare(strict_types=1);


namespace Currency\DataProvider;

use Currency\Entity\EntityInterface;

interface DataProviderInterface
{
    /**
     * @return \Generator<EntityInterface>
     */
    public function getData(): \Generator;
}
