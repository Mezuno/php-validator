<?php

namespace Mezuno\Validator\Rules;

interface Rule
{
    /**
     * Place here your validation condition
     *
     * @param array $data
     * @param string $field
     * @return bool
     */
    public function passes(array $data, string $field): bool;
}