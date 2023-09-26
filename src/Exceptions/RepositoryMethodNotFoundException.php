<?php

namespace Mezuno\Validator\Exceptions;

class RepositoryMethodNotFoundException extends \Exception
{
    public function __construct(string $message = "Repository method not found.", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}