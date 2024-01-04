<?php declare( strict_types = 1 );

namespace App\ApiController;

use App\Repository\RequestRepository;
use Siestacat\SymfonyJsonErrorResponse\JsonErrorResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/status', name: 'api_status', methods: ['GET'])]
class StatusController extends JsonErrorResponse
{
    public function __construct
    (
        private RequestRepository $requestRepository
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

            return new JsonResponse(['success' => true, 'uploaded' => $document_request->uploaded]);

        }
        catch(\Exception $e)
        {
            return new JsonResponse(['error' => $e->getMessage(), 'trace' => $e->getTrace()]);
        }
    }
}