<?php

namespace Mezuno\Validator\Rules\Specific;

use Mezuno\Validator\Exceptions\RepositoryMethodNotFoundException;
use Mezuno\Validator\Exceptions\RepositoryNotFoundException;
use Mezuno\Validator\Rules\AbstractRule;
use Mezuno\Validator\Rules\Rule;

class ExistsRule extends AbstractRule implements Rule
{
    /** @inheritdoc  */
    public static string $alias = 'exists';

    /** @inheritdoc  */
    public function passes(array $data, string $field): bool
    {
        $this->params = explode(',', $this->params['exists']);

        if (!class_exists($this->params[0])) {
            throw new RepositoryNotFoundException('Repository ' . $this->params[0] . ' not found.');
        }

        $repository = new $this->params[0]();
        $method = $this->params[1];

        if (!method_exists($repository, $method)) {
            throw new RepositoryMethodNotFoundException('Method ' . $method . ' not found in ' . $repository . '.');
        }

        $result = $repository->{$method};

        return boolval($result);
    }
}