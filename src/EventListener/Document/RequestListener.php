<?php declare( strict_types = 1 );

namespace App\EventListener\Document;

use App\Document\Request;
use App\EventListener\Document\Exception\TokenMinLengthException;

class RequestListener
{

    const TOKEN_LENGTH_MIN = 6;

    public function __construct
    (
        private int $token_length,
        private int $default_max_bytes,
        private int $default_max_bytes_per_file,
        private int $default_max_files
    ){}

    public function postPersist(Request $document_request): void
    {
        $this->checkTokenLenght();

        $document_request->upload_token = \IcyApril\CryptoLib::randomString($this->token_length);

        foreach($this->getDefaultValues() as $property_name => $default)
        {
            if(!isset($document_request->{$property_name})) $document_request->{$property_name} = $default;
        }
    }

    public function checkTokenLenght():void
    {
        if($this->token_length < self::TOKEN_LENGTH_MIN)
        {
            throw new TokenMinLengthException(self::TOKEN_LENGTH_MIN);
        }
    }

    public function getDefaultValues():array
    {
        return
        [
            'max_bytes' => $this->default_max_bytes,
            'max_bytes_per_file' => $this->default_max_bytes_per_file,
            'max_files' => $this->default_max_files
        ];
    }
}