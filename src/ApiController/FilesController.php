<?php declare( strict_types = 1 );

namespace App\ApiController;

use App\Repository\RequestRepository;
use App\Service\Api\GetFilesApiService;
use App\Service\Api\RequestApiService;
use Siestacat\SymfonyJsonErrorResponse\JsonErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/files', name: 'api_files', methods: ['GET'])]
class FilesController extends JsonErrorResponse
{
    public function __construct
    (
        private RequestRepository $requestRepository,
        private RequestStack $requestStack,
        private GetFilesApiService $getFilesApiService
    ){}

    #[Route('/{upload_token}', name: '')]
    public function index(string $upload_token):JsonResponse
    {
        try
        {
            $document_request = $this->requestRepository->getByUploadByToken($upload_token);

            if($document_request === null)
            {
                return $this->json_error_message('upload_token_expired');
            }

            return new JsonResponse(['success' => true, 'files' => $this->getFilesApiService->getFiles($document_request)]);

        }
        catch(\Exception $e)
        {
            return new JsonResponse(['error' => $e->getMessage(), 'trace' => $e->getTrace()]);
        }
    }
}