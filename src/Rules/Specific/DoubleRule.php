<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class DoubleRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'double';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        return is_double($data[$field]);
    }
}