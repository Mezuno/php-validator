<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class BooleanRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'bool';

    /** @inheritdoc */
    public function passes(array $data, string $field): bool
    {
        return is_bool(filter_var($data[$field], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    }
}