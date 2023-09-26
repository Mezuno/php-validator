<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class MaxRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'max';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        if (is_numeric($data[$field])) return $this->params['max'] >= $data[$field];
        if (is_array($data[$field])) return $this->params['max'] >= count($data[$field]);
        if (is_string($data[$field])) return $this->params['max'] >= strlen($data[$field]);

        return false;
    }
}