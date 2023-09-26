<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class StringRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'string';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        return is_string($data[$field]);
    }
}