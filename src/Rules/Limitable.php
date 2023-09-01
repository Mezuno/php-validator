<?php

namespace Mezuno\Validator\Rules;

trait Limitable
{
    /**
     * Миниальное значение
     *
     * @var int|null
     */
    private ?int $min = null;

    /**
     * Максимальное значение
     *
     * @var int|null
     */
    private ?int $max = null;

    /**
     * Установить минимальное значение
     *
     * @param int $min
     * @return $this
     */
    public function min(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Установить максимальное значение
     *
     * @param int $max
     * @return $this
     */
    public function max(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Получить минимальное значение
     *
     * @return int
     */
    protected function getMin(): int
    {
        return $this->min;
    }

    /**
     * Получить максимальное значение
     *
     * @return int
     */
    protected function getMax(): int
    {
        return $this->max;
    }

    /**
     * Имеет установленное минимальное значение
     *
     * @return bool
     */
    protected function hasMin(): bool
    {
        return !is_null($this->min);
    }

    /**
     * Имеет установленное максимальное значение
     *
     * @return bool
     */
    protected function hasMax(): bool
    {
        return !is_null($this->max);
    }
}