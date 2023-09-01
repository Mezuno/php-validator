<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Exceptions\ValidationException;
use Mezuno\Validator\Rules\Abstract\AbstractRules;

class StringRules extends AbstractRules
{
    use Limitable;

    /**
     * Название ожидаемого типа данных
     *
     * @var string
     */
    protected static string $type = 'string';

    /** @inheritDoc */
    public function valid(array $data, string $field, array $exceptions = [])
    {
        parent::valid($data, $field, $exceptions);

        if ($this->hasMin() && $this->hasValue() && strlen($this->getValue()) < $this->getMin()) {

            throw $this->exceptions['min'] ??
                new ValidationException('Field ' . $field . ' must contain >= ' . $this->getMin() . ' characters. Current value: ' . strlen($this->getValue()), $this->field);
        }

        if ($this->hasMax() && $this->hasValue() && strlen($this->getValue()) > $this->getMax()) {

            throw $this->exceptions['max'] ??
                new ValidationException('Field ' . $field . ' must contain <= ' . $this->getMax() . ' characters. Current value: ' . strlen($this->getValue()), $this->field);
        }

        return $this->getValue();
    }

    /** @inheritDoc */
    protected function hasValidType(): bool
    {
        return is_string($this->getValue());
    }

    /**
     * Имеется ли поле в реквесте
     *
     * @return bool
     */
    protected function hasDataValue(): bool
    {
        return parent::hasDataValue() && (!empty($this->getDataValue()) || false === $this->getDataValue());
    }
}