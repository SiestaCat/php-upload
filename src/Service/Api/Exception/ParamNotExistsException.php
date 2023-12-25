<?php declare( strict_types = 1 );

namespace App\Service\Api\Exception;

use App\Document\Request;

class ParamNotExistsException extends \Exception
{
    public function __construct(string $param_name)
    {
        parent::__construct(sprintf('Param %s not exists in %s', $param_name, Request::class));
    }
}