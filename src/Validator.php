<?php

namespace Mezuno\Validator;

use Mezuno\Validator\Exceptions\RuleNotFoundException;
use Mezuno\Validator\Rules\Specific\RequiredRule;
use Mezuno\Validator\Tools\ValidatorMessages;
use Mezuno\Validator\Tools\ValidatorParser;

class Validator
{
    use ValidatorParser;

    /**
     * Errors occurred during validation
     *
     * @var array
     */
    protected array $errors = [];

    /**
     * Handled fields
     *
     * @var array
     */
    protected array $handled = [];

    /**
     * Run validation
     *
     * @param array $data
     * @param array $rulesArray
     * @param array $messages
     * @return void
     * @throws RuleNotFoundException
     */
    public function validate(array $data, array $rulesArray, array $messages = []): void
    {
        $this->boot($messages);

        foreach ($rulesArray as $field => $rules) {

            $rules = $this->convertRulesToArray($rules);

            $this->handleRules($rules, $data, $field);
        }
    }

    /**
     * Boot validator
     *
     * @param array $messages
     * @return void
     */
    private function boot(array $messages = []): void
    {
        ValidatorMessages::boot($messages);
        ValidatorRulesMap::boot();
    }

    /**
     * Set new error
     *
     * @param string $ruleAlias
     * @param string $field
     * @param array $params
     * @return void
     */
    private function setError(string $ruleAlias, string $field, array $params): void
    {
        $this->errors[$field][$ruleAlias] = ValidatorMessages::get($ruleAlias, $field, $params);
    }

    /**
     * Register new custom rule
     *
     * @param string $rule
     * @return void
     */
    public function registerRule(string $rule): void
    {
        ValidatorRulesMap::register($rule);
    }

    /**
     * Get all errors that occurred during validation
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Handle rules
     *
     * @param array $rules
     * @param array $data
     * @param string $field
     * @return void
     */
    private function handleRules(array $rules, array $data, string $field): void
    {
        $this->handleFirstlyRules($rules, $data, $field);

        if ($this->fieldHandled($data, $field)) {
            return;
        }

        foreach ($rules as $rule) {

            if (!$rule->passes($data, $field)) {

                $this->setError($rule::getAlias(), $field, $rule->getParams());
            }
        }
    }

    /**
     * Handle rules that must be handled firstly
     *
     * @param $rules
     * @param $data
     * @param $field
     * @return void
     */
    private function handleFirstlyRules($rules, $data, $field): void
    {
        foreach ($rules as $rule) {

            if ($rule instanceof RequiredRule) {
                if (!$rule->passes($data, $field)) {

                    $this->setError('required', $field, $rule->getParams());
                }

                $this->handled[$field][$rule::getAlias()] = true;
            }
        }
    }

    /**
     * Field handled by required rule
     *
     * @param array $data
     * @param string $field
     * @return bool
     */
    public function fieldHandled(array $data, string $field): bool
    {
        return !isset($data[$field]) && $this->handled[$field]['required'];
    }
}