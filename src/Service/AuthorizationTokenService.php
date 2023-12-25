<?php declare( strict_types = 1 );

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Attribute\AsRoutingConditionService;
use Symfony\Component\HttpFoundation\Request;

#[AsRoutingConditionService]
class AuthorizationTokenService
{
    public function __construct(private string $token){}

    public function check(Request $request): bool
    {
        return $request->headers->get('authorization') === 'Basic ' . $this->token;
    }
}