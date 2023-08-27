<?php

declare(strict_types=1);

namespace Currency\Repository\SearchCondition;

enum Condition : string
{
    case AND = 'AND';

    case OR = 'OR';
}
