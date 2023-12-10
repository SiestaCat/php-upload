<?php

namespace App\ApiController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/request', name: 'api_request')]
class Request extends AbstractController
{
    #[Route('/', name: '')]
    public function index():JsonResponse
    {
        return new JsonResponse;
    }
}