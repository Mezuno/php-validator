<?php

namespace Mezuno\Validator\Tools;

use Mezuno\Validator\Exceptions\RuleNotFoundException;
use Mezuno\Validator\ValidatorRulesMap;

trait ValidatorParser
{
    /**
     * @param array|string $rules
     * @return array
     * @throws RuleNotFoundException
     */
    private function convertRulesToArray(array|string $rules): array
    {
        if (is_string($rules)) {

            $rules = explode('|', $rules);

            foreach ($rules as &$rule) {

                [$ruleAlias, $values] = $this->separateValues($rule);

                if (!isset(ValidatorRulesMap::get()[$ruleAlias])) {
                    throw new RuleNotFoundException('Rule "' . $rule . '" not found.');
                }

                $rule = new (ValidatorRulesMap::get()[$ruleAlias]);

                $rule->setParams($values);
            }
        }

        return $rules;
    }

    /**
     * @param $rule
     * @return array[string, array]
     */
    private function separateValues($rule): array
    {
        if (str_contains($rule, ':')) {

            $ruleParts = explode(':', $rule);

            $ruleAlias = $ruleParts[0];

            if (str_contains(',', $ruleParts[1])) {

                $values[$ruleAlias] = explode(',', $ruleParts[1]);
            } else {

                $values[$ruleAlias] = $ruleParts[1];
            }

        } else {

            $ruleAlias = $rule;
            $values = [];
        }

        return [$ruleAlias, $values];
    }
}