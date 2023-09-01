<?php

namespace Mezuno\Validator\Rules\Abstract;

use Mezuno\Validator\Rules\ValidationRules as ValidationRulesInterface;
use Mezuno\Validator\Exceptions\ValidationException;

abstract class AbstractRules implements ValidationRulesInterface
{
    /**
     * Обязательно ли поле к заполнению
     *
     * @var bool
     */
    protected bool $required = false;

    /**
     * Может ли поле быть NULL
     *
     * @var bool
     */
    protected bool $nullable = false;

    /**
     * Стандартное значение поля
     *
     * @var mixed|null
     */
    protected mixed $default = null;

    /**
     * Название поля, к которому применяются правила
     *
     * @var string
     */
    protected string $field;

    /**
     * Название ожидаемого типа данных
     *
     * @var string
     */
    protected static string $type;

    /**
     * Валидируемые данные
     *
     * @var array
     */
    private array $data;

    /**
     * Кастомные эксепшены
     *
     * @var array
     */
    protected array $exceptions = [];

    /**
     * Поле должно существовать в БД
     *
     * @var bool
     */
    protected bool $mustExists = false;

    /**
     * Репозиторий, в котором ищется значение
     *
     * @var string
     */
    protected ?string $existsRepository = null;

    /**
     * Метод, по которому ищется поле в $existsRepository
     *
     * @var string
     */
    protected ?string $existsMethod = null;

    /**
     * Имеет ли поле валидный тип данных
     *
     * @return bool
     */
    abstract protected function hasValidType(): bool;

    /** @inheritdoc */
    final public static function make(): static
    {
        return new static();
    }

    /** @inheritDoc */
    final public function required(callable $callable = null): static
    {
        $this->required = is_null($callable) ? true : $callable();
        $this->nullable = !$this->required;

        return $this;
    }

    /** @inheritDoc */
    final public function nullable(callable $callable = null): self
    {
        $this->nullable = is_null($callable) ? true : $callable();
        $this->required = !$this->nullable;

        return $this;
    }

    /** @inheritDoc */
    final public function default($default): self
    {
        $this->default = $default;

        return $this;
    }

    /** @inheritDoc */
    final public function exists(string $repository, string $method): self
    {
        $this->existsRepository = $repository;
        $this->existsMethod = $method;
        $this->mustExists = true;

        return $this;
    }

    /** @inheritDoc */
    public function valid(array $data, string $field, array $exceptions = [])
    {
        $this->field = $field;
        $this->data = $data;
        $this->exceptions = $exceptions;

        if ($this->isRequired() && !$this->hasValue()) {

            throw $this->exceptions['required'] ?? new ValidationException($this->field . ' field is required.', $this->field);
        }

        if (!$this->hasValidType() && !($this->isNullable() && $this->isValueNull())) {

            throw $this->exceptions['type'] ?? new ValidationException($this->getValidTypeExceptionMessage(), $this->field);
        }

        if ($this->mustExists && $this->isRequired()) {

            if (is_null($this->existsRepository)) {
                throw new ValidationException('Enter repository for search field ' . $this->field . '.', $this->field);
            }

            if (is_null($this->existsMethod)) {
                throw new ValidationException('Enter repository method for search field ' . $this->field . '.', $this->field);
            }

            $found = (new $this->existsRepository)->{$this->existsMethod}($this->getValue());

            if (!$found) {

                throw new ValidationException('Field ' . $this->field . ' must exists in database.', $this->field);
            }
        }
    }

    /**
     * Возвращает строку для ValidationException с сообщением о несоответствии ожидаемого типа с полученным
     *
     * @return string
     */
    private function getValidTypeExceptionMessage(): string
    {
        return 'Field ' . $this->field . ' must be type of ' . static::$type . ', ' . gettype($this->getValue()) . ' given.';
    }

    /**
     * Получить значение поля
     *
     * Если в реквесте имеется поле, возвращает его
     *
     * Если нет, возвращает стандартное
     *
     * @return mixed
     */
    protected function getValue()
    {
        if ($this->hasDataValue()) {
            return $this->getDataValue();
        }

        return $this->getDefaultValue();
    }

    /**
     * Имеет ли поле стандартное значение, или значение из реквеста
     *
     * @return bool
     */
    protected function hasValue(): bool
    {
        return $this->hasDataValue() || $this->hasDefault();
    }

    /**
     * Получить значение поля в реквесте
     *
     * @return mixed
     */
    protected function getDataValue(): mixed
    {
        return $this->data[$this->field];
    }

    /**
     * Получить стандартное значение для поля
     *
     * @return mixed
     */
    protected function getDefaultValue(): mixed
    {
        return $this->default;
    }

    /**
     * Имеется ли поле в реквесте
     *
     * @return bool
     */
    protected function hasDataValue(): bool
    {
        return isset($this->data[$this->field]);
    }

    /**
     * Обязательно ли поле к заполнению
     *
     * @return bool
     */
    final protected function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Может ли поле быть NULL
     *
     * @return bool
     */
    final protected function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * Является ли значение поля NULL
     *
     * @return bool
     */
    final protected function isValueNull(): bool
    {
        return is_null($this->getValue());
    }

    /**
     * Имеет ли поле стандартное значение
     *
     * @return bool
     */
    final protected function hasDefault(): bool
    {
        return !is_null($this->default);
    }

    /**
     * Должно ли поле сущестовать в БД
     *
     * @return bool
     */
    final protected function mustExists(): bool
    {
        return $this->mustExists;
    }
}