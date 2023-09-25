<?php

return [

    // TYPE
    'type' => [
        'int' => 'Field %s must be type of int. %s given.',
        'float' => 'Field %s must be type of float. %s given.',
        'bool' => 'Field %s must be type of bool. %s given.',
        'array' => 'Field %s must be type of array. %s given.',
        'string' => 'Field %s must be type of string. %s given.',
        'date' => 'Field %s must be type of date. %s given.',
        'email' => 'Field %s must be type of email. %s given.',
    ],

    // REQUIRED
    'required' => [
        'default' => '%s field is required.',
        'array' => '%s must be not empty array.',
    ],

    // EXISTS
    'repository' => 'Enter repository for search field %s.',
    'method' => 'Enter repository method for search field %s.',
    'exists' => 'Field %s must exists in database.',

    // MIN
    'min' => [
        'numeric' => 'Field %s must be <= %d. Current value: %d',
        'string' => 'Field %s must be <= %d. Current value: %d',
    ],

    // MAX
    'max' => [
        'numeric' => 'Field %s must be >= %d. Current value: %d',
        'string' => 'Field %s must be >= %d. Current value: %d',
    ],
];