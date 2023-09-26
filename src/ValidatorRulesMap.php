<?php

namespace Mezuno\Validator;

use Mezuno\Validator\Rules\Rule;

class ValidatorRulesMap
{
    /**
     * All default rules
     *
     * @var array|string[]
     */
    protected static array $rules = [];

    /**
     * Array of custom rules
     *
     * @var array
     */
    protected static array $customRules = [];

    /**
     * Boot rules map
     *
     * @return void
     */
    public static function boot(): void
    {
        self::setDefaultRules();
    }

    /**
     * Get rules map
     *
     * @return array
     */
    public static function get(): array
    {
        return array_merge(self::$rules, self::$customRules);
    }

    /**
     * Sets default rules
     *
     * @return void
     */
    protected static function setDefaultRules(): void
    {
        foreach (scandir(__DIR__ . '/Rules/Specific/') as $rule) {
            if ($rule !== '.' && $rule !== '..') {

                /** @var Rule $ruleClass */
                $ruleClass = '\Mezuno\Validator\Rules\Specific\\' . str_replace('.php', '', $rule);

                self::$rules[$ruleClass::getAlias()] = $ruleClass;
            }
        }
    }

    /**
     * Register custom rule
     *
     * @param $rule
     * @return void
     */
    public static function register($rule): void
    {
        self::$customRules[$rule::getAlias()] = $rule;
    }
}