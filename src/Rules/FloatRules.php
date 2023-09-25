<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\NumericRules;

final class FloatRules extends NumericRules
{
    /**
     * Name of expected type of field.
     *
     * @var string
     */
    protected static string $type = 'float';

    /** @inheritDoc */
    public function valid(array $data, string $field, array $messages = [])
    {
        parent::valid($data, $field, $messages);

        return $this->getValue();
    }

    /** @inheritDoc */
    protected function hasValidType(): bool
    {
        return is_float(floatval($this->getValue())) && parent::hasValidType();
    }
}