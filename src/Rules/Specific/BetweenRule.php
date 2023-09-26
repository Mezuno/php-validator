<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class BetweenRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'between';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        $this->params = explode(',', $this->params['between']);

        if (is_numeric($data[$field])) return $this->params[0] < $data[$field] && $this->params[1] > $data[$field];
        if (is_array($data[$field])) return $this->params[0] < count($data[$field]) && $this->params[1] > count($data[$field]);
        if (is_string($data[$field])) return $this->params[0] < strlen($data[$field]) && $this->params[1] > strlen($data[$field]);

        return false;
    }
}