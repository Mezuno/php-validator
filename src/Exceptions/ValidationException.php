<?php

namespace Mezuno\Validator\Exceptions;

class ValidationException extends \Exception
{
    /**
     * Поле, данные в котором не валидны
     *
     * @var string
     */
    private string $field;

    public function __construct(
        protected $message,
        string $field,
        protected $code = 400
    )
    {
        parent::__construct($message, $code);

        $this->field = $field;
    }

    /**
     * Получить имя невалидного поля
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }
}