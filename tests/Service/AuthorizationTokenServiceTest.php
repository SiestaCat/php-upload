<?php declare( strict_types = 1 );

namespace App\Tests\Service;

use App\Service\AuthorizationTokenService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AuthorizationTokenServiceTest extends TestCase
{
    public function testCheckWithValidToken()
    {
        $token = 'your_token_here';
        $service = new AuthorizationTokenService($token);

        $request = $this->createMock(Request::class);
        $request->headers = new class {
            public function get($header)
            {
                return 'Basic your_token_here';
            }
        };

        $this->assertTrue($service->check($request));
    }

    public function testCheckWithInvalidToken()
    {
        $token = 'your_token_here';
        $service = new AuthorizationTokenService($token);

        $request = $this->createMock(Request::class);
        $request->headers = new class {
            public function get($header)
            {
                return 'Basic invalid_token';
            }
        };

        $this->assertFalse($service->check($request));
    }
}