<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class ArrayRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'array';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        return is_array($data[$field]);
    }
}