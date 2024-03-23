<?php

declare(strict_types=1);

namespace Currency\Enum;


enum Code: string
{

    case USD = 'USD';

    case EUR = 'EUR';

    case UAH = 'UAH';

    case PLN = 'PLN';

    private static function getNumMap(): array
    {
        return [
            Code::UAH->value => '980',
            Code::EUR->value => '978',
            Code::USD->value => '840',
            Code::PLN->value => '985',
        ];
    }

    public function getNum(): string
    {
        return self::getNumMap()[$this->value];
    }

    public static function tryFromNum(string $num): ?Code
    {
        $code = array_flip(self::getNumMap())[$num] ?? null;

        if (null === $code) {
            return null;
        }

        return Code::tryFrom($code);
    }
}
