<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class NullableRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'nullable';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        return true;
    }
}