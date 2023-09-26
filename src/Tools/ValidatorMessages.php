<?php

namespace Mezuno\Validator\Tools;

use Adbar\Dot;

final class ValidatorMessages
{
    /**
     * Messages in dot notation
     *
     * @var Dot
     */
    private static Dot $messages;

    /**
     * Custom messages in dot notation
     *
     * @var Dot
     */
    protected static Dot $customMessages;

    /**
     * Load dot notation for default & custom messages
     *
     * @param array $customMessages
     * @return void
     */
    public static function boot(array $customMessages = []): void
    {
        self::loadDot($customMessages);
    }

    /**
     * Get message by dot notation path.
     *
     * @param string $path
     * @param string $field
     * @param array|string $replace
     * @return string
     */
    public static function get(string $path, string $field, array|string $replace): string
    {
        $replace = is_array($replace) ? $replace : [$replace];

        array_unshift($replace, $field);

        return self::$customMessages->get($field . '.' . $path) ?? sprintf(self::$messages->get($path), ...$replace);
    }

    /**
     * Upload messages array in dot object.
     *
     * @param array $customMessages
     * @return void
     */
    private static function loadDot(array $customMessages = []): void
    {
        self::$messages = new Dot();
        self::$messages->setArray(require 'messages.php');

        self::$customMessages = new Dot();

        foreach ($customMessages as $key => $message) {
            self::$customMessages->set($key, $message);
        }
    }
}