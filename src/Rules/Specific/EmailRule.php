<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class EmailRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'email';

    /** @inheritdoc */
    public function passes(array $data, string $field): bool
    {
        return filter_var($data[$field], FILTER_VALIDATE_EMAIL);
    }
}