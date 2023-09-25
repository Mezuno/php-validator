<?php

namespace Mezuno\Validator\Rules\Traits;

trait Limitable
{
    /**
     * Min value
     *
     * @var int|null
     */
    private ?int $min = null;

    /**
     * Max value
     *
     * @var int|null
     */
    private ?int $max = null;

    /**
     * Set min value
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
     * Set max value
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
     * Get min value
     *
     * @return int
     */
    protected function getMin(): int
    {
        return $this->min;
    }

    /**
     * Get max value
     *
     * @return int
     */
    protected function getMax(): int
    {
        return $this->max;
    }

    /**
     * Has min value
     *
     * @return bool
     */
    protected function hasMin(): bool
    {
        return !is_null($this->min);
    }

    /**
     * Has max value
     *
     * @return bool
     */
    protected function hasMax(): bool
    {
        return !is_null($this->max);
    }
}