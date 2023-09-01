<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Exceptions\ValidationException;

final class EmailRules extends StringRules
{

    /** @inheritDoc */
    public function valid(array $data, string $field, array $exceptions = [])
    {
        parent::valid($data, $field, $exceptions);

        return $this->getValue();
    }

    /** @inheritDoc */
    protected function hasValidType(): bool
    {
        if (!filter_var($this->getValue(), FILTER_VALIDATE_EMAIL) && $this->hasValue() && !empty($this->getValue())) {
            throw $this->exceptions['email'] ??
                new ValidationException('Field ' . $this->field . ' must be valid E-mail.', $this->field);
        }

        return true;
    }
}