<?php

namespace Mezuno\Validator;

if (!function_exists('message')) {

    /**
     * Get validation message from pool.
     *
     * @param string $path
     * @param array|string $replace
     * @param string|null $custom
     * @return string
     */
    function message(string $path, array|string $replace, ?string $custom = null): string
    {
        return Tools\ValidatorMessages::get($path, $replace, $custom);
    }
}