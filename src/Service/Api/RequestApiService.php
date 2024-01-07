<?php declare( strict_types = 1 );

namespace App\Service\Api;

use App\Document\Request;
use App\Service\Api\Exception\ParamNotExistsException;

class RequestApiService
{

    const INT_PARAMS_EXPECTED = 
    [
        'max_bytes',
        'max_bytes_per_file',
        'max_files'
    ];

    public function getDocumentFromJson(mixed $input_json, array $int_params = self::INT_PARAMS_EXPECTED):Request
    {
        $document_request = new Request;

        if(!(is_object($input_json) && get_class($input_json) === \stdClass::class)) return $document_request;

        foreach($int_params as $param_name) $this->populateParam($param_name, $input_json, $document_request, 'integer');

        $this->populateParam('webhook_upload', $input_json, $document_request, 'string');

        return $document_request;
    }

    private function populateParam(string $param_name, \stdClass $input_json, Request $document_request, string $type):void
    {
        if(!property_exists($input_json, $param_name)) return;

        if(!property_exists($document_request, $param_name)) throw new ParamNotExistsException($param_name);

        $param_value = $input_json->{$param_name};

        if($type === 'integer' && is_float($param_value)) $param_value = intval($param_value);

        if(gettype($param_value) !== $type) throw new \TypeError(sprintf('Param %s should be %s', $param_name, $type));

        $document_request->{$param_name} = $param_value;
    }
}