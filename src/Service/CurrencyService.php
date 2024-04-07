<?php

declare(strict_types=1);

namespace Currency\Service;

use Currency\Enum\Code;
use Currency\Repository\RepositoryInterface;
use Currency\Repository\SearchCondition\ComplexSearchCondition;
use Currency\Repository\SearchCondition\Condition;
use Currency\Repository\SearchCondition\Currency\CodeSearchCondition;
use Currency\Repository\SearchCondition\Rate\CodeSearchCondition as RateCurrencyCodeSearchCondition;

class CurrencyService implements CurrencyServiceInterface
{
    public function __construct(
        private readonly RepositoryInterface $CurrencyRepository,
        private readonly RepositoryInterface $rateRepository,
    ) {
    }
    /**
     * @inheritDoc
     */
    public function getByCodes(Code ...$codes): \Traversable
    {
        $searchCondition = new ComplexSearchCondition(
            Condition::OR,
            ...array_map(fn (Code $code) => new CodeSearchCondition($code), $codes)
        );

        return $this->CurrencyRepository->getBy($searchCondition);
    }

    public function getCurrentRate(
        Code ...$codes,
    ): \Traversable {
        $searchCondition = new ComplexSearchCondition(
            Condition::OR,
            ...array_map(fn (Code $code) => new RateCurrencyCodeSearchCondition($code), $codes)
        );

        return $this->rateRepository->getBy($searchCondition);
    }
}
