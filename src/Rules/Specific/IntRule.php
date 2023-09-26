<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class IntRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'int';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        return is_int($data[$field]);
    }
}