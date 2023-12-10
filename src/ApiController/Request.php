<?php

namespace App\ApiController;

use App\Document\Request as DocumentRequest;
use App\Repository\RequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/request', name: 'api_request')]
class Request extends AbstractController
{

    public function __construct
    (
        private RequestRepository $requestRepository
    ){}

    #[Route('/', name: '')]
    public function index():JsonResponse
    {
        $document_request = $this->requestRepository->create();

        return new JsonResponse(['upload_token' => $document_request->upload_token]);
    }
}