<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Rules\Abstract\AbstractRules;
use function Mezuno\Validator\message;

final class ArrayRules extends AbstractRules
{
    protected const ITEMS_EXCEPTION_FIELD = 'items';
    protected const NESTED_ITEMS_EXCEPTION_FIELD = 'nested_items';

    /**
     * Name of expected type of field
     *
     * @var string
     */
    protected static string $type = 'array';

    /**
     * Rules for validation array items
     *
     * @var array
     */
    protected array $itemsRules = [];

    /**
     * Rules for validation nested array items
     *
     * @var array
     */
    protected array $nestedItemsRules = [];

    /**
     * Set rules for validation array items
     *
     * @param array $itemsRules
     * @param bool $nested
     * @return ArrayRules
     */
    public function items(array $itemsRules, bool $nested = false): self
    {
        if ($nested) {
            $this->nestedItemsRules = $itemsRules;
        } else {
            $this->itemsRules = $itemsRules;
        }

        return $this;
    }

    /** @inheritdoc  */
    public function valid(array $data, string $field, array $messages = [])
    {
        parent::valid($data, $field, $messages);

        if (empty($data[$field])) {
            $this->errors['required'] = message('required.array', $this->field, $this->customMessages('required'));
        }

        /** Next comes the logic for processing the rules of the array elements */

        $returnData = [];

        /** If the rules for array elements are not empty */
        if (!empty($this->getItemsRules())) {

            /** We loop through the rules and apply them to elements */
            foreach ($this->getItemsRules() as $subfield => $itemRules) {

                $returnData[$subfield] =
                    $itemRules->valid(
                        $data[$this->field],
                        $subfield,
                        $messages[$this->field][self::ITEMS_EXCEPTION_FIELD][$subfield] ?? []
                    );
            }
        }

        /** If the rules for elements of nested arrays are not empty */
        if (!empty($this->getNestedItemsRules())) {

            /** Loop through nested arrays */
            foreach ($data[$field] as $subArray) {

                $validatedSubItem = [];

                /** Apply rules to each element of the nested array */
                foreach ($this->getNestedItemsRules() as $nestedItemField => $nestedItemsRule) {
                    $validatedSubItem[$nestedItemField] =
                        $nestedItemsRule
                            ->valid(
                                $subArray,
                                $nestedItemField,
                                $messages[self::NESTED_ITEMS_EXCEPTION_FIELD][$nestedItemField] ?? []
                            );
                }

                $returnData[] = $validatedSubItem;
            }
        }

        return $returnData;
    }

    /**
     * Get array element validation rules
     *
     * @return array
     */
    public function getItemsRules(): array
    {
        return $this->itemsRules;
    }

    /**
     * Get nested array element validation rules
     *
     * @param string|null $field
     * @return AbstractRules[]|AbstractRules
     */
    public function getNestedItemsRules(?string $field = null): array|AbstractRules
    {
        if ($field) {
            return $this->nestedItemsRules[$field];
        }

        return $this->nestedItemsRules;
    }

    /** @inheritdoc  */
    function hasValidType(): bool
    {
        return is_array($this->getValue());
    }
}