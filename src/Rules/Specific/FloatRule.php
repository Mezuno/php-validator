<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class FloatRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'float';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        return is_float($data[$field]);
    }
}