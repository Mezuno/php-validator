<?php

namespace Mezuno\Validator\Rules;

abstract class AbstractRule
{
    /**
     * Alias for use rule in string
     *
     * @var string
     */
    protected static string $alias;

    /**
     * Rule params
     *
     * @var array
     */
    protected array $params;

    /**
     * @param mixed $params
     */
    public function __construct(mixed $params = [])
    {
        $this->setParams($params);
    }

    /**
     * Set rule params
     *
     * @param mixed $params
     * @return void
     */
    public function setParams(mixed $params): void
    {
        $this->params = is_array($params) ? $params : [$params];
    }

    /**
     * Get rule params
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Get rule alias
     *
     * @return string
     */
    public static function getAlias(): string
    {
        return static::$alias;
    }
}