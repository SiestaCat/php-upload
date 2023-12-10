<?php

namespace App\EventListener\Document\Exception;


class TokenMinLengthException extends \Exception
{
    public function __construct(int $min_length)
    {
        parent::__construct(sprintf('Upload token length should be greater than %d', $min_length));
    }
}