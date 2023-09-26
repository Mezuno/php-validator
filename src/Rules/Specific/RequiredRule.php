<?php

namespace Mezuno\Validator\Rules\Specific;

use Closure;
use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class RequiredRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'required';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        if (isset($this->params[0]) && $this->params[0] instanceof Closure) {
            if (!$this->params[0]($data)) {

                return true;
            }
        }

        return isset($data[$field]);
    }
}