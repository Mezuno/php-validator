<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class DateRule extends AbstractRule implements Rule
{
    /** @inheritdoc */
    public static string $alias = 'date';

    /** @inheritdoc */
    public function passes(array $data, string $field): bool
    {
        return is_numeric(strtotime($data[$field]));
    }
}