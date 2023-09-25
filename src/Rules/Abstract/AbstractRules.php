<?php

namespace Mezuno\Validator\Rules\Abstract;

use Mezuno\Validator\Contracts\ValidationRules as ValidationRulesInterface;
use function Mezuno\Validator\message;

abstract class AbstractRules implements ValidationRulesInterface
{
    /**
     * Required field.
     *
     * @var bool
     */
    protected bool $required = false;

    /**
     * Can field be NULL.
     *
     * @var bool
     */
    protected bool $nullable = false;

    /**
     * Default value for field.
     *
     * @var mixed|null
     */
    protected mixed $default = null;

    /**
     * Name of the field to which the rules apply.
     *
     * @var string
     */
    protected string $field;

    /**
     * Name of expected type of field.
     *
     * @var string
     */
    protected static string $type;

    /**
     * Data for validation.
     *
     * @var array
     */
    private array $data;

    /**
     * Errors, occurred during validation.
     *
     * @var array
     */
    protected array $errors = [];

    /**
     * Error messages for current field.
     *
     * @var array
     */
    private array $messages = [];

    /**
     * Field must exist in database.
     *
     * @var bool
     */
    protected bool $mustExists = false;

    /**
     * Repository for exists rule.
     *
     * @var string|null
     */
    protected ?string $existsRepository = null;

    /**
     * Repository method for exists rule
     *
     * @var string|null
     */
    protected ?string $existsMethod = null;

    /**
     * Field has valid type function.
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
    final public function errors(): array
    {
        return $this->errors;
    }

    /** @inheritDoc */
    public function valid(array $data, string $field, array $messages = [])
    {
        $this->setFields($data, $field, $messages);

        $this->checkRequired();
        $this->checkType();
        $this->checkExists();
    }

    /**
     * Check required rule for current field.
     *
     * @return void
     */
    private function checkRequired(): void
    {
        if ($this->isRequired() && !$this->hasValue()) {

            $this->errors['required'] =
                message('required.default', $this->field, $this->customMessages('required'));
        }
    }

    /**
     * Check type for current field.
     *
     * @return void
     */
    private function checkType(): void
    {
        if (!$this->hasValidType() && !($this->isNullable() && $this->isValueNull()) && empty($this->errors['required'])) {

            $this->errors['type'] =
                message(
                    'type.' . static::$type,
                    [
                        $this->field,
                        gettype($this->getDataValue())
                    ],
                    $this->customMessages('type'),
                );
        }
    }

    /**
     * Check exists rule for current field.
     *
     * @return void
     */
    private function checkExists(): void
    {
        if ($this->mustExists && $this->isRequired()) {

            if (is_null($this->existsRepository)) {
                $this->errors['repository'] =
                    message('repository', $this->field, $this->customMessages('repository'));
            }

            if (is_null($this->existsMethod)) {
                $this->errors['method'] =
                    message('method', $this->field, $this->customMessages('method'));
            }

            $found = (new $this->existsRepository)->{$this->existsMethod}($this->getValue());

            if (!$found) {

                $this->errors['exists'] =
                    message('exists', $this->field, $this->customMessages('exists'));
            }
        }
    }

    /**
     * Get custom message
     *
     * @param string $type
     * @return string|null
     */
    protected function customMessages(string $type): ?string
    {
        return $this->messages[$type] ?? null;
    }

    /**
     * Set general fields of rule
     *
     * @param array $data
     * @param string $field
     * @param array $messages
     * @return void
     */
    private function setFields(array $data, string $field, array $messages = []): void
    {
        $this->field = $field;
        $this->data = $data;
        $this->messages = $messages;
    }

    /**
     * Get field value
     *
     * If data contains a field, returns it
     *
     * If not, returns default
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
     * Does the field have a standard value, or the value from the request
     *
     * @return bool
     */
    protected function hasValue(): bool
    {
        return $this->hasDataValue() || $this->hasDefault();
    }

    /**
     * Get field value in $data
     *
     * @return mixed
     */
    protected function getDataValue(): mixed
    {
        return $this->data[$this->field];
    }

    /**
     * Get the default value for a field
     *
     * @return mixed
     */
    protected function getDefaultValue(): mixed
    {
        return $this->default;
    }

    /**
     * Is there a field in $data
     *
     * @return bool
     */
    protected function hasDataValue(): bool
    {
        return isset($this->data[$this->field]);
    }

    /**
     * Is field required
     *
     * @return bool
     */
    final protected function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Can a field be NULL
     *
     * @return bool
     */
    final protected function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * Is the field value NULL
     *
     * @return bool
     */
    final protected function isValueNull(): bool
    {
        return is_null($this->getValue());
    }

    /**
     * Does the field have a default value
     *
     * @return bool
     */
    final protected function hasDefault(): bool
    {
        return !is_null($this->default);
    }

    /**
     * Must the field exist in the database
     *
     * @return bool
     */
    final protected function mustExists(): bool
    {
        return $this->mustExists;
    }
}