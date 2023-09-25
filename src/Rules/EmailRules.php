<?php

namespace Mezuno\Validator\Rules;

use function Mezuno\Validator\message;

final class EmailRules extends StringRules
{
    /**
     * Name of expected type of field.
     *
     * @var string
     */
    protected static string $type = 'email';

    /** @inheritDoc */
    public function valid(array $data, string $field, array $messages = [])
    {
        parent::valid($data, $field, $messages);

        return $this->getValue();
    }

    /** @inheritDoc */
    protected function hasValidType(): bool
    {
        if (!filter_var($this->getValue(), FILTER_VALIDATE_EMAIL) && $this->hasValue() && !empty($this->getValue())) {

            $this->errors['email'] =
                message('type.email', $this->field, $this->customMessages('email'));
        }

        return true;
    }
}