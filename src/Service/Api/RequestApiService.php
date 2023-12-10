<?php declare( strict_types = 1 );

namespace App\Service\Api;

use App\Document\Request;

class RequestApiService
{

    const INT_PARAMS_EXPECTED = 
    [
        'max_bytes',
        'max_bytes_per_file',
        'max_files'
    ];

    public function getDocumentFromJson(mixed $input_json):Request
    {
        $document_request = new Request;

        if(!(is_object($input_json) && get_class($input_json) === \stdClass::class)) return $document_request;

        foreach(self::INT_PARAMS_EXPECTED as $param_name) $this->populateIntParam($param_name, $input_json, $document_request);

        return $document_request;
    }

    private function populateIntParam(string $param_name, \stdClass $input_json, Request $document_request):void
    {
        if(!property_exists($input_json, $param_name)) return;

        if(!property_exists($document_request, $param_name)) throw new \Exception(sprintf('Param %s not exists in %s', $param_name, Request::class));

        $param_value = $input_json->{$param_name};

        if(is_float($param_value)) $param_value = intval($param_value);

        if(!is_int($param_value)) throw new \Exception(sprintf('Param %s should be int', $param_name));

        $document_request->{$param_name} = $param_value;
    }
}