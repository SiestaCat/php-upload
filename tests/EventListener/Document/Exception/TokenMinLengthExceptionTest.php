<?php declare( strict_types = 1 );

namespace App\EventListener\Document\Tests\Exception;
use PHPUnit\Framework\TestCase;
use App\EventListener\Document\Exception\TokenMinLengthException;
use App\EventListener\Document\RequestListener;

class TokenMinLengthExceptionTest extends TestCase
{
    public function test(): void
    {
        $this->expectException(TokenMinLengthException::class);

        throw new TokenMinLengthException(RequestListener::TOKEN_LENGTH_MIN);
    }
}