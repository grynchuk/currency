<?php

declare(strict_types=1);

namespace Currency\Service\Conversion;

interface ConversionServiceInterface
{
    public function convert(ConversionContext $conversionContext): float;
}
