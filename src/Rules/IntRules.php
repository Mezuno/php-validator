<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\NumericRules;

final class IntRules extends NumericRules
{
    /**
     * Название ожидаемого типа данных
     *
     * @var string
     */
    protected static string $type = 'int';

    /** @inheritdoc */
    public function valid(array $data, string $field, array $exceptions = []): int
    {
        parent::valid($data, $field, $exceptions);

        return intval($this->getValue());
    }

    /** @inheritdoc  */
    function hasValidType(): bool
    {
        return is_int(intval($this->getValue())) && parent::hasValidType();
    }
}