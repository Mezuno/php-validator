<?php

namespace Mezuno\Validator\Rules\Abstract;

use Mezuno\Validator\Rules\Traits\Limitable;
use function Mezuno\Validator\message;

abstract class NumericRules extends AbstractRules
{
    use Limitable;

    /**
     * Name of expected type of field.
     *
     * @var string
     */
    protected static string $type = 'numeric';

    /** @inheritdoc */
    public function valid(array $data, string $field, array $messages = [])
    {
        parent::valid($data, $field, $messages);

        $this->checkMin();
        $this->checkMax();
    }

    /**
     * Checks is data more than min length.
     *
     * @return void
     */
    private function checkMin(): void
    {
        if (
            $this->hasMin() &&
            $this->getDataValue() < $this->getMin() &&
            $this->hasValue() &&
            empty($this->errors['required']) &&
            empty($this->errors['type'])
        ) {
            $this->errors['min'] =
                message('min.numeric',
                    [
                        $this->field,
                        $this->getMin(),
                        $this->getDataValue(),
                    ],
                    $this->customMessages('min'),
                );
        }
    }

    /**
     * Checks is data less than max length.
     *
     * @return void
     */
    private function checkMax(): void
    {
        if (
            $this->hasMax() &&
            $this->getDataValue() < $this->getMax() &&
            $this->hasValue() &&
            empty($this->errors['required']) &&
            empty($this->errors['type'])
        ) {
            $this->errors['max'] =
                message('max.numeric',
                    [
                        $this->field,
                        $this->getMax(),
                        $this->getDataValue(),
                    ],
                    $this->customMessages('max'),
                );
        }
    }

    /** @inheritdoc */
    protected function hasValidType(): bool
    {
        return is_numeric($this->getValue());
    }
}