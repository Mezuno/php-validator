<?php

namespace Mezuno\Validator;

use Adbar\Dot;
use Mezuno\Validator\Rules\Abstract\AbstractRules;

class Validator
{
    private array $errors = [];

    /**
     * Validates all fields in $data according to the rules from $rulesArray
     *
     * Does not return fields that are not in $rulesArray, but are in $data
     *
     * @param array $data
     * @param AbstractRules[] $rulesArray
     * @param array $messages
     * @return array
     */
    public function validate(array $data, array $rulesArray, array $messages = []): array
    {
        require 'helpers.php';

        $messagesDot = (new Dot($messages, true));

        $returnData = [];

        foreach ($rulesArray as $field => $rules) {
            $returnData[$field] = $rules->valid($data, $field, $messagesDot->get($field) ?? []);

            if (!empty($rules->errors())) {

                $this->errors[$field] = array_values($rules->errors());
            }
        }

        return $returnData;
    }

    /**
     * Get all errors that occurred during data array validation
     *
     * @param string|null $field
     * @return array
     */
    public function errors(string $field = null): array
    {
        if (!is_null($field)) {
            return $this->errors[$field];
        }

        return $this->errors;
    }
}