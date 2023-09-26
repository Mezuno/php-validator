<?php

namespace Mezuno\Validator\Exceptions;

class RuleNotFoundException extends \Exception
{
    public function __construct(string $message = "Rule not found.", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}