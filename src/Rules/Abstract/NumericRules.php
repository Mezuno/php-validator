<?php

namespace Mezuno\Validator\Rules\Abstract;

use Mezuno\Validator\Exceptions\ValidationException;
use Mezuno\Validator\Rules\Limitable;

abstract class NumericRules extends AbstractRules
{
    use Limitable;

    /** @inheritdoc */
    public function valid(array $data, string $field, array $exceptions = [])
    {
        parent::valid($data, $field, $exceptions);

        if ($this->hasMin() && $this->getValue() < $this->getMin() && $this->hasValue()) {

            throw $this->exceptions['min'] ??
                new ValidationException('Field ' . $this->field . ' must be >= ' . $this->getMin() . '. Current value: ' . $this->getValue(), $this->field);
        }

        if ($this->hasMax() && $this->getValue() > $this->getMax() && $this->hasValue()) {

            throw $this->exceptions['max'] ??
                new ValidationException('Field ' . $this->field . ' must be <= ' . $this->getMax() . '. Current value: ' . $this->getValue(), $this->field);
        }
    }

    /** @inheritdoc  */
    protected function hasValidType(): bool
    {
        return is_numeric($this->getValue());
    }
}