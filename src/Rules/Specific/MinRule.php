<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class MinRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'min';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        if (is_numeric($data[$field])) return $this->params['min'] <= $data[$field];
        if (is_array($data[$field])) return $this->params['min'] <= count($data[$field]);
        if (is_string($data[$field])) return $this->params['min'] <= strlen($data[$field]);

        return false;
    }
}