<?php

namespace App\ApiController;

use App\Document\Request as DocumentRequest;
use App\Repository\RequestRepository;
use App\Service\Api\RequestApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/request', name: 'api_request')]
class Request extends AbstractController
{

    public function __construct
    (
        private RequestRepository $requestRepository,
        private RequestStack $requestStack,
        private RequestApiService $requestApiService
    ){}

    #[Route('/', name: '')]
    public function index():JsonResponse
    {
        try
        {
            $document_request = $this->requestApiService->getDocumentFromJson(json_decode($this->requestStack->getCurrentRequest()->getContent(), true));

            $this->requestRepository->create($document_request);

            return new JsonResponse(['upload_token' => $document_request->upload_token]);
        }
        catch(\Exception $e)
        {
            return new JsonResponse(['error' => $e->getMessage(), 'trace' => $e->getTrace()]);
        }
    }
}