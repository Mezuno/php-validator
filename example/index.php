<?php

require 'bootstrap.php';

use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;
use Mezuno\Validator\Rules\Specific\FloatRule;
use Mezuno\Validator\Rules\Specific\MinRule;
use Mezuno\Validator\Rules\Specific\RequiredRule;
use Mezuno\Validator\Validator;
use Mezuno\Validator\ValidatorRulesMap;

$data = [
    'integer_field'     => 0,
    'string_field'      => 'beauty girl',
    'email_field'      => 'email',
    'array_field'       => [1, 2, 3, 4],
];

$rules = [
    'integer_field'     => 'int|required|min:1|max:5',
    'email_field'       => 'email|required|exists:UserRepository,findByEmail',
    'string_field'      => 'string|required|min:6|max:11',
    'array_field'       => 'array|required|between:1,3',
    'float_field'       => [
        new RequiredRule(function ($data) {
            return $data['integer_field'] < 10;
        }),
        new FloatRule(),
        new MinRule(1),
    ]
];

$messages = [
    'integer_field.min'     => 'field integer_field must be >= 5.',
    'integer_field.int'     => 'field integer_field must be type of int.',
    'string_field.beauty'   => 'String must be beauty!',
    'email_field.email'     => 'Incorrect E-mail!',
];

$validator = new Validator();

$validator->validate($data, $rules, $messages);

print_r($validator->errors());
