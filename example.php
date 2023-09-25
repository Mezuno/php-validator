<?php

/**
 * -----------------------------------------------
 * Example of usage Mezuno\Validator.
 * -----------------------------------------------
 *
 *  This is example of usage. Here you can find some ways to use it.
 *  For more information, please, open readme.md
 *
 * @author Kirill Tsibulskiy <mekishido@gmail.com>
 */

require __DIR__ . '/vendor/autoload.php';

use Mezuno\Validator\Rules\ArrayRules;
use Mezuno\Validator\Rules\BoolRules;
use Mezuno\Validator\Rules\IntRules;
use Mezuno\Validator\Rules\StringRules;

use Mezuno\Validator\Validator;

/*
 * -----------------------------------------------
 * Input data.
 * -----------------------------------------------
 *
 * Input data is array with keys from everywhere
 * you can get it.
 *
 */

$data = [
    'integer_field' => 'not integer',   // specially place string in field that must be type of int
    // specially don`t enter string_field
    'array_field' => [
        'id' => 0,
        'name' => 'John Doe',
    ],
    'bool_field' => true,
];


/*
 * -----------------------------------------------
 * Validation Rules.
 * -----------------------------------------------
 *
 * Validation Rules is array with keys = field names
 * in input data and values like Rules::make(). Each
 * of rule has required(), nullable() and others
 * functions for configure.
 *
 */

$rules = [
    'integer_field' => IntRules::make()->min(1)->max(4),
    'string_field' => StringRules::make()->required(),
    'bool_field' => BoolRules::make(),

    'array_field' => ArrayRules::make()->items([
        'id' => IntRules::make()->min(1),
        'name' => StringRules::make()->min(2),
    ]),

];


/*
 * -----------------------------------------------
 * Custom messages for errors.
 * -----------------------------------------------
 *
 * This is example of custom messages you can define.
 * Keys of messages are set in dot notation.
 *
 */

$messages = [
    'integer_field.type' => 'integer_field must be type of integer. Be more careful!',
    'string_field.required' => 'string_field is required. Now you know it.',
];

/*
 * -----------------------------------------------
 * Validator usage.
 * -----------------------------------------------
 *
 * 1. Create validator instance.
 * 2. Call ->validate on instance with params:
 *      $data, $rules, $messages
 *
 */

$validator = new Validator();
$validated = $validator->validate($data, $rules, $messages);


/*
 * -----------------------------------------------
 * Validation errors.
 * -----------------------------------------------
 *
 * This is example how you can get validation
 * errors that occurred during validation.
 *
 */

foreach ($validator->errors() as $keyFieldError => $fieldErrors) {

    echo 'Field: ' . $keyFieldError . PHP_EOL;
    foreach ($fieldErrors as $keyError => $error) {

        echo ++$keyError . ' => ' . $error . PHP_EOL;
    }
}
