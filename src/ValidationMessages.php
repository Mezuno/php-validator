<?php

namespace Mezuno\Validator;

use Adbar\Dot;

final class ValidationMessages
{
    private static Dot $messages;

    /**
     * Get message by dot notation path.
     *
     * @param string $path
     * @param array|string $replace
     * @param string|null $custom
     * @return string
     */
    public static function message(string $path, array|string $replace, ?string $custom = null): string
    {
        self::loadDot();

        $message = $custom ?? self::$messages->get($path);

        $replace = is_array($replace) ? $replace : [$replace];

        return sprintf($message, ...$replace);
    }

    /**
     * Upload messages array in dot object.
     *
     * @return void
     */
    private static function loadDot(): void
    {
        if (!isset(self::$messages)) {

            self::$messages = new Dot();
            self::$messages->setArray(require 'messages.php');
        }
    }
}