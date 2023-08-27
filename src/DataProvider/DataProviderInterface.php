<?php

declare(strict_types=1);


namespace Currency\DataProvider;

interface DataProviderInterface
{
    public function getData(): array;
}
