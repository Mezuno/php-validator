<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\NumericRules;

final class FloatRules extends NumericRules
{
    /**
     * Название ожидаемого типа данных
     *
     * @var string
     */
    protected static string $type = 'float';

    /** @inheritDoc */
    public function valid(array $data, string $field, array $exceptions = [])
    {
        parent::valid($data, $field, $exceptions);

        return $this->getValue();
    }

    /** @inheritDoc */
    protected function hasValidType(): bool
    {
        return is_float(floatval($this->getValue())) && parent::hasValidType();
    }
}