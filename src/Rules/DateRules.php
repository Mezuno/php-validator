<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\AbstractRules;

class DateRules extends AbstractRules
{
    /**
     * Название ожидаемого типа данных
     *
     * @var string
     */
    protected static string $type = 'date';


    /** @inheritDoc */
    public function valid(array $data, string $field, array $exceptions = [])
    {
        parent::valid($data, $field, $exceptions);

        return $this->getValue();
    }

    /** @inheritDoc */
    protected function hasValidType(): bool
    {
        return $this->hasValue() && is_numeric(strtotime($this->getValue()));
    }
}