<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Exceptions\ValidationException;

interface ValidationRules
{
    /**
     * Создает инстанс класса
     *
     * @return self
     */
    public static function make(): static;

    /**
     * Устанавливает значение обязательности поля
     *
     * @param callable|null $callable
     * @return static
     */
    public function required(callable $callable = null): static;

    /**
     * Устанавливает значение обязательности поля и возможности полю быть NULL
     *
     * @param callable|null $callable
     * @return self
     */
    public function nullable(callable $callable = null): self;

    /**
     * Устанавливает стандартное значение, которое будет использоваться если поля не окажется в $request
     *
     * @param $default
     * @return self
     */
    public function default($default): self;

    /**
     * Установить, что поле должно существовать в $repository по $method
     *
     * @param string $repository
     * @param string $method
     * @return self
     */
    public function exists(string $repository, string $method): self;

    /**
     * Проверяет, соответствует ли поле $field в $data из реквеста заданным правилам
     *
     * Выкидывает ValidationException при несоответствии любому из правил
     *
     * Возвращает валидное значение $field
     *
     * @param array $data
     * @param string $field
     * @param array $exceptions
     * @return mixed
     * @throws ValidationException
     */
    public function valid(array $data, string $field, array $exceptions = []);
}