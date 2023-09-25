<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\NumericRules;

final class IntRules extends NumericRules
{
    /**
     * Name of expected type of field.
     *
     * @var string
     */
    protected static string $type = 'int';

    /** @inheritdoc */
    public function valid(array $data, string $field, array $messages = []): int
    {
        parent::valid($data, $field, $messages);

        return intval($this->getValue());
    }

    /** @inheritdoc */
    function hasValidType(): bool
    {
        return is_int(intval($this->getValue())) && parent::hasValidType();
    }
}