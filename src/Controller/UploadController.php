<?php declare( strict_types = 1 );

namespace App\Controller;

use App\Repository\RequestRepository;
use App\Service\Upload\CalculateTotalSizeService;
use App\Service\Upload\FileSizeValidatorService;
use App\Service\Upload\GetFilesFromRequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Siestacat\SymfonyJsonErrorResponse\JsonErrorResponse;

#[Route('/upload', name: 'upload')]
class UploadController extends JsonErrorResponse
{

    public function __construct
    (
        private RequestRepository $requestRepository,
        private RequestStack $requestStack,
        private GetFilesFromRequestService $getFilesFromRequestService,
        private CalculateTotalSizeService $calculateTotalSizeService,
        private FileSizeValidatorService $fileSizeValidatorService
    ){}

    #[Route('/{upload_token}', name: '')]
    public function index(string $upload_token):JsonResponse
    {
        try
        {
            $document_request = $this->requestRepository->getByToken($upload_token);

            if($document_request === null)
            {
                return $this->json_error_message('upload_token_expired');
            }

            $files = $this->getFilesFromRequestService->get($this->requestStack->getCurrentRequest());

            if(count($files) > $document_request->max_files)
            {
                return $this->json_error_message('max_files_reached');
            }

            if($this->calculateTotalSizeService->getSize($files) > $document_request->max_bytes)
            {
                return $this->json_error_message('max_bytes_reached');
            }

            foreach($files as $file)
            {
                if(!$this->fileSizeValidatorService->check_size($file, $document_request->max_bytes_per_file))
                {
                    return $this->json_error_message('max_bytes_per_file_reached');
                }
            }
        }
        catch(\Exception $e)
        {
            return $this->json_error($e);
        }
    }
}