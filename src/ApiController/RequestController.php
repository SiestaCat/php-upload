<?php declare( strict_types = 1 );

namespace App\ApiController;

use App\Repository\RequestRepository;
use App\Service\Api\RequestApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/request', name: 'api_request', methods: ['GET'])]
class RequestController extends AbstractController
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

            $this->requestRepository->documentManager->persist($document_request);
            $this->requestRepository->documentManager->flush();

            return new JsonResponse(['upload_token' => $document_request->upload_token]);
        }
        catch(\Exception $e)
        {
            return new JsonResponse(['error' => $e->getMessage(), 'trace' => $e->getTrace()]);
        }
    }
}