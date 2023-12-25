<?php declare( strict_types = 1 );

namespace App\Service\Api\Tests\Exception;
use PHPUnit\Framework\TestCase;
use App\Service\Api\Exception\ParamNotExistsException;

class ParamNotExistsExceptionTest extends TestCase
{
    public function test(): void
    {
        $this->expectException(ParamNotExistsException::class);

        throw new ParamNotExistsException('param_name');
    }
}