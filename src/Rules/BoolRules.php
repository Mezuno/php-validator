<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\AbstractRules;

final class BoolRules extends AbstractRules
{
    /**
     * Название ожидаемого типа данных
     *
     * @var string
     */
    protected static string $type = 'bool';

    /** @inheritdoc */
    public function valid(array $data, string $field, array $exceptions = []): bool
    {
        parent::valid($data, $field, $exceptions);

        return filter_var($this->getValue(), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /** @inheritdoc  */
    function hasValidType(): bool
    {
        return is_bool(filter_var($this->getValue(), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    }
}