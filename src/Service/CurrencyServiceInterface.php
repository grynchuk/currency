<?php

declare(strict_types=1);

namespace Currency\Service;

use Currency\Entity\Currecny;
use Currency\Enum\Code;

interface CurrencyServiceInterface
{
    /**
     * @param Code ...$codes
     *
     * @return \Traversable<Currecny>
     */
    public function getByCodes(
        Code ... $codes
    ): \Traversable;

    public function getCurrentRate(
        Code ... $codes
    ): \Traversable;
}
