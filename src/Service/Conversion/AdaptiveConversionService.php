<?php

declare(strict_types=1);

namespace Currency\Service\Conversion;

use Currency\Service\Conversion\ConversionServiceInterface;

class AdaptiveConversionService implements ConversionServiceInterface
{
    public function convert(ConversionContext $conversionContext): float
    {
        if (
            null !== $conversionContext->sourceCurrencyRate->sell
            && null !== $conversionContext->targetCurrencyRate->buy
        ) {
            $amount = round($conversionContext->amount, $conversionContext->sourceCurrency->precision);
            $convertedAmount = $amount * $conversionContext->sourceCurrencyRate->sell / $conversionContext->targetCurrencyRate->buy;

            return round($convertedAmount, $conversionContext->targetCurrency->precision);
        }

        if (
            null === $conversionContext->sourceCurrencyRate->mid
            || null === $conversionContext->targetCurrencyRate->mid
        ) {
            throw new \RuntimeException('Invalid rate context');
        }

        $amount = round($conversionContext->amount, $conversionContext->sourceCurrency->precision);
        $convertedAmount = $amount * $conversionContext->sourceCurrencyRate->mid / $conversionContext->targetCurrencyRate->mid;

        return round($convertedAmount, $conversionContext->targetCurrency->precision);
    }
}
