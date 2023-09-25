<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\AbstractRules;

class DateRules extends AbstractRules
{
    /**
     * Name of expected type of field.
     *
     * @var string
     */
    protected static string $type = 'date';


    /** @inheritDoc */
    public function valid(array $data, string $field, array $messages = [])
    {
        parent::valid($data, $field, $messages);

        return $this->getValue();
    }

    /** @inheritDoc */
    protected function hasValidType(): bool
    {
        return $this->hasValue() && is_numeric(strtotime($this->getValue()));
    }
}