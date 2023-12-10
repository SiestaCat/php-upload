<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Attribute\AsRoutingConditionService;
use Symfony\Component\HttpFoundation\Request;

#[AsRoutingConditionService]
class AuthorizationTokenService
{
    public function __construct(private string $token){}

    public function check(Request $request): bool
    {
        return true;
        return $request->headers->get('authorization') === 'Basic ' . $this->token;
    }
}