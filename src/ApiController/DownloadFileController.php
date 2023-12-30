<?php declare( strict_types = 1 );

namespace App\ApiController;

use App\Repository\FileRepository;
use App\Repository\RequestRepository;
use App\Service\Api\GetFilesApiService;
use App\Service\Api\RequestApiService;
use App\Service\FileStorageService;
use Siestacat\SymfonyJsonErrorResponse\JsonErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/download', name: 'api_download', methods: ['GET'])]
class DownloadFileController extends JsonErrorResponse
{
    public function __construct
    (
        private RequestRepository $requestRepository,
        private FileRepository $fileRepository,
        private FileStorageService $fileStorageService
    ){}

    #[Route('/{upload_token}/{hash}', name: '')]
    public function index(string $upload_token, string $hash):Response
    {
        $document_request = $this->requestRepository->getByUploadByToken($upload_token);

        if($document_request === null)
        {
            throw new NotFoundHttpException(sprintf('Document request with upload token "%s" not exists', $upload_token));
        }

        $document_file = $this->fileRepository->getFileByRequestAndHash($document_request, $hash);

        if($document_file === null)
        {
            throw new NotFoundHttpException(sprintf('File with request upload token "%s" and hash "%s" not exists', $upload_token, $hash));
        }

        $resource = $this->fileStorageService->getStream($document_file);

        return (new StreamedResponse())->setCallback(function () use ($resource): void {
            while (!feof($resource))
            {
                echo fread($resource, 1024);
                flush();
            }
            fclose($resource);
        });

    }
}