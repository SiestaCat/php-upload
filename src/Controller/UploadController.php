<?php declare( strict_types = 1 );

namespace App\Controller;

use App\Document\File;
use App\Document\Request;
use App\Repository\FileRepository;
use App\Repository\RequestRepository;
use App\Service\FileStorageService;
use App\Service\Upload\CalculateTotalSizeService;
use App\Service\Upload\FileSizeValidatorService;
use App\Service\Upload\GetFilesFromRequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Siestacat\SymfonyJsonErrorResponse\JsonErrorResponse;
use Psr\Log\LoggerInterface;

#[Route('/upload', name: 'upload')]
class UploadController extends JsonErrorResponse
{

    public function __construct
    (
        private RequestRepository $requestRepository,
        private FileRepository $fileRepository,
        private RequestStack $requestStack,
        private GetFilesFromRequestService $getFilesFromRequestService,
        private CalculateTotalSizeService $calculateTotalSizeService,
        private FileSizeValidatorService $fileSizeValidatorService,
        private FileStorageService $fileStorageService,
        private LoggerInterface $logger
    ){}

    #[Route('/{upload_token}', name: '', methods: ['POST'])]
    public function index(string $upload_token):JsonResponse
    {
        try
        {
            $document_request = $this->requestRepository->getPendingByUploadByToken($upload_token);

            if($document_request === null)
            {
                return $this->json_error_message('upload_token_expired');
            }

            $files = $this->getFilesFromRequestService->get($this->requestStack->getCurrentRequest());

            if(count($files) === 0)
            {
                return $this->json_error_message('no_files');
            }

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
                if(!($file->getError() === UPLOAD_ERR_OK))
                {
                    continue;
                }

                if(!$this->fileSizeValidatorService->check_size($file, $document_request->max_bytes_per_file))
                {
                    return $this->json_error_message('max_bytes_per_file_reached');
                }

                $document_file = $this->fileRepository->create($document_request, $file, false);

                if($this->fileStorageService->exists($document_file))
                {
                    continue;
                }

                if(!$this->fileStorageService->writeFromLocal($file->getRealPath(), $document_file))
                {
                    return $this->json_error_message('unknow_error');
                }

                $this->fileRepository->documentManager->persist($document_file);
            }

            $this->fileRepository->documentManager->flush();

            $this->fileRepository->documentManager->clear();

            if($this->fileRepository->getCountByRequest($document_request) === 0)
            {
                return $this->json_error_message('unknow_error');
            }

            $this->requestRepository->setUploaded($document_request);

            $this->sendWebHook($document_request);

            return new JsonResponse(['success' => true]);
        }
        catch(\Exception $e)
        {
            $this->logger->error($e, ['trace' => $e->getTraceAsString()]);
            return $this->json_error($e);
        }
    }

    private function sendWebHook(Request $document_request):void
    {

        if($document_request->webhook_upload === null) return;

        $curl = curl_init();

        $post = [
            'upload_token' => $document_request->upload_token
        ];

        curl_setopt($curl, CURLOPT_URL, $document_request->webhook_upload);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

        curl_exec($curl);

        curl_close($curl);
    }
}