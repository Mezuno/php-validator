<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\AbstractRules;

final class BoolRules extends AbstractRules
{
    /**
     * Name of expected type of field.
     *
     * @var string
     */
    protected static string $type = 'bool';

    /** @inheritdoc */
    public function valid(array $data, string $field, array $messages = []): bool
    {
        parent::valid($data, $field, $messages);

        return filter_var($this->getValue(), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /** @inheritdoc  */
    function hasValidType(): bool
    {
        return is_bool(filter_var($this->getValue(), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    }
}