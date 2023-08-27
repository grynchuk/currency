<?php

declare(strict_types=1);

namespace Currency\Service\Conversion;

use Currency\Entity\Currecny;
use Currency\Entity\Rate;

final class ConversionContext
{
    public function __construct(
        public readonly float $amount,
        public readonly Rate $sourceCurrencyRate,
        public readonly Rate $targetCurrencyRate,
        public readonly Currecny $sourceCurrency,
        public readonly Currecny $targetCurrency,
    ) {
        if (false === $this->isRateDateValid()) {
            throw new \RuntimeException('Rate date is invalid');
        }

        if (false === $this->isCurrencyValid()) {
            throw new \RuntimeException('Rate currency is invalid');
        }
    }

    private function isRateDateValid(): bool
    {
        return $this->sourceCurrencyRate->date->format('d-m-Y') ===  $this->targetCurrencyRate->date->format('d-m-Y');
    }

    private function isCurrencyValid(): bool
    {
        return $this->sourceCurrencyRate->currencyCode === $this->sourceCurrency->code
            && $this->targetCurrencyRate->currencyCode === $this->targetCurrency->code;
    }
}
