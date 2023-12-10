<?php declare( strict_types = 1 );

namespace App\EventListener\Document;

use App\Document\Request;
use App\EventListener\Document\Exception\TokenMinLengthException;

class RequestListener
{

    const TOKEN_LENGTH_MIN = 6;

    public function __construct(private int $token_length)
    {
        if($this->token_length < self::TOKEN_LENGTH_MIN)
        {
            throw new TokenMinLengthException(self::TOKEN_LENGTH_MIN);
        }
    }

    public function postPersist(Request $document_request): void
    {
        $document_request->upload_token = \IcyApril\CryptoLib::randomString($this->token_length);
    }
}