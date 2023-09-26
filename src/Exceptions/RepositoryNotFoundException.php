<?php

namespace Mezuno\Validator\Exceptions;

class RepositoryNotFoundException extends \Exception
{
    public function __construct(string $message = "Repository not found.", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}