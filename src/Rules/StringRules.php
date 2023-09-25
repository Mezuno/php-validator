<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\AbstractRules;
use Mezuno\Validator\Rules\Traits\Limitable;
use function Mezuno\Validator\message;

class StringRules extends AbstractRules
{
    use Limitable;

    /**
     * Name of expected type of field.
     *
     * @var string
     */
    protected static string $type = 'string';

    /** @inheritDoc */
    public function valid(array $data, string $field, array $messages = [])
    {
        parent::valid($data, $field, $messages);

        $this->checkMin();
        $this->checkMax();

        return $this->getValue();
    }

    /**
     * Checks is data more than min length.
     *
     * @return void
     */
    private function checkMin(): void
    {
        if ($this->hasMin() && $this->hasValue() && strlen($this->getDataValue()) < $this->getMin() && empty($this->errors['required'])) {

            $this->errors['min'] =
                message(
                    'min.string',
                    [
                        $this->field,
                        $this->getMin(),
                        strlen($this->getDataValue())
                    ],
                    $this->customMessages('min')
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
        if ($this->hasMax() && $this->hasValue() && strlen($this->getDataValue()) > $this->getMax() && empty($this->errors['required'])) {

            $this->errors['max'] =
                message(
                    'max.string',
                    [
                        $this->field,
                        $this->getMin(),
                        strlen($this->getDataValue())
                    ],
                    $this->customMessages('max')
                );
        }
    }

    /** @inheritDoc */
    protected function hasValidType(): bool
    {
        return is_string($this->getValue());
    }

    /**
     * Is there a field in the request.
     *
     * @return bool
     */
    protected function hasDataValue(): bool
    {
        return parent::hasDataValue() && (!empty($this->getDataValue()) || false === $this->getDataValue());
    }
}