<?php


use Mezuno\Validator\Exceptions\ValidationException;
use Mezuno\Validator\Rules\Abstract\AbstractRules;

class Validator
{
    /**
     * Валидирует все поля в $request по правилам из $rulesArray
     *
     * Не возвращает поля, которых нет в $rulesArray, но есть в $request
     *
     * @param array $data
     * @param AbstractRules[] $rulesArray
     * @param array $exceptions
     * @return array
     * @throws ValidationException
     */
    public static function validate(array $data, array $rulesArray, array $exceptions = []): array
    {
        $returnData = [];

        foreach ($rulesArray as $field => $rules) {
            $returnData[$field] = $rules->valid($data, $field, $exceptions[$field] ?? []);
        }

        return $returnData;
    }
}