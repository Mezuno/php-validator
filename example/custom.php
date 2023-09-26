<?php

require 'bootstrap.php';

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;
use Mezuno\Validator\Validator;
use Mezuno\Validator\ValidatorRulesMap;

class BeautyRule extends AbstractRule implements Rule
{
    public static string $alias = 'beauty';

    /** @inheritDoc */
    public function passes(array $data, string $field): bool
    {
        return str_contains($data[$field], 'beauty');
    }
}

ValidatorRulesMap::register(BeautyRule::class);

$data = [
    'string_field'          => 'beuty girl', // special mistake
];

$rules = [
    'string_field'          => 'string|beauty',
];

$messages = [
    'string_field.beauty'   => 'String must contain "beauty"!',
];

$validator = new Validator();

$validator->validate($data, $rules, $messages);

var_dump($validator->errors());