<?php

namespace Mezuno\Validator\Rules;

use Mezuno\Validator\Exceptions\ValidationException;
use Mezuno\Validator\Rules\Abstract\AbstractRules;

final class ArrayRules extends AbstractRules
{
    protected const ITEMS_EXCEPTION_FIELD = 'items';
    protected const NESTED_ITEMS_EXCEPTION_FIELD = 'nested_items';

    /**
     * Название ожидаемого типа данных
     *
     * @var string
     */
    protected static string $type = 'array';

    /**
     * Правила валидации элементов массива
     *
     * @var array
     */
    protected array $itemsRules = [];

    /**
     * Правила валидации элементов каждого вложенного массива
     *
     * @var array
     */
    protected array $nestedItemsRules = [];

    /**
     * Установить правила для элементов массива
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
    public function valid(array $data, string $field, array $exceptions = [])
    {
        parent::valid($data, $field, $exceptions);

        if (empty($data[$field])) {
            throw new ValidationException(__('array.must_be_not_empty.error', $field), $field);
        }

        /** Дальше идет логика обработки элементов массива и элементов вложенных массивов */

        $returnData = [];

        /** Если не пусты правила для элементов массива */
        if (!empty($this->getItemsRules())) {

            /** Перебираем правила и применяем к элементам */
            foreach ($this->getItemsRules() as $subfield => $itemRules) {

                $returnData[$subfield] =
                    $itemRules->valid(
                        $data[$this->field],
                        $subfield,
                        $exceptions[$this->field][self::ITEMS_EXCEPTION_FIELD][$subfield] ?? []
                    );
            }
        }

        /** Если не пусты правила для элементов вложенных массивов */
        if (!empty($this->getNestedItemsRules())) {

            /** Перебираем вложенные массивы */
            foreach ($data[$field] as $subArray) {

                $validatedSubItem = [];

                /** Применяем к каждому элементу вложенного массива правила */
                foreach ($this->getNestedItemsRules() as $nestedItemField => $nestedItemsRule) {
                    $validatedSubItem[$nestedItemField] =
                        $nestedItemsRule
                            ->valid(
                                $subArray,
                                $nestedItemField,
                                $exceptions[self::NESTED_ITEMS_EXCEPTION_FIELD][$nestedItemField] ?? []
                            );
                }

                $returnData[] = $validatedSubItem;
            }
        }

        return $returnData;
    }

    /**
     * Получить правила валидации элементов массива
     *
     * @return array
     */
    public function getItemsRules(): array
    {
        return $this->itemsRules;
    }

    /**
     * Получить правила валидации элементов массива
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