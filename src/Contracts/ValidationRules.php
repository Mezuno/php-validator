<?php

namespace Mezuno\Validator\Contracts;

interface ValidationRules
{
    /**
     * Creates instance of class
     *
     * @return self
     */
    public static function make(): static;

    /**
     * Set the required value of a field
     *
     * @param callable|null $callable
     * @return static
     */
    public function required(callable $callable = null): static;

    /**
     * Sets the value of whether a field is required and whether the field can be NULL
     *
     * @param callable|null $callable
     * @return self
     */
    public function nullable(callable $callable = null): self;

    /**
     * Sets the default value that will be used if the field is not in $data
     *
     * @param $default
     * @return self
     */
    public function default($default): self;

    /**
     * Set a field to exist in $repository by $method
     *
     * @param string $repository
     * @param string $method
     * @return self
     */
    public function exists(string $repository, string $method): self;

    /**
     * Checks if the $field in $data from the request matches the given rules
     *
     * Logs an error if any of the rules are not met
     *
     * Returns a valid $field value
     *
     * @param array $data
     * @param string $field
     * @param array $messages
     * @return mixed
     */
    public function valid(array $data, string $field, array $messages = []);

    /**
     * Get array of errors occurred during validation field.
     *
     * @return array
     */
    public function errors(): array;
}