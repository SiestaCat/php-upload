<?php declare( strict_types = 1 );

namespace App\Tests\ApiController;

use App\Document\Request;
use App\Document\File;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\FileRepository;
use App\Repository\RequestRepository;
use App\Tests\CommonTestMethods;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Service\FileStorageService;

class DownloadFileControllerTest extends WebTestCase
{
    private RequestRepository $request_repository;

    private FileRepository $file_repository;

    private KernelBrowser $client;

    private ContainerInterface $container;

    private FileStorageService $fileStorageService;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->container = $this->client->getContainer();

        $this->fileStorageService = $this->container->get('test.' . FileStorageService::class);

        $this->request_repository = $this->container->get('test.' . RequestRepository::class);
        $this->file_repository = $this->container->get('test.' . FileRepository::class);
    }

    public function test():void
    {
        $files = $this->createRequestAndFiles();

        foreach($files as $file)
        {
            $this->client->followRedirects(true);

            $this->client->request('GET', '/api/download/' . $file->request->upload_token . '/' . $file->hash, [], [], [
                'HTTP_Authorization' => sprintf('Basic %s', $this->container->getParameter('api_token'))
            ]);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * @return File[]
     */
    private function createRequestAndFiles():array
    {
        $document_request = new Request;

        $document_request->uploaded = true;

        $this->request_repository->documentManager->persist($document_request);

        $this->file_repository->documentManager->flush();

        $files_documents = [];

        for($i=1;$i<=10;$i++)
        {
            $uploaded_file = CommonTestMethods::genRandomUploadedFile(1024*1024);

            $document_file = $this->file_repository->create($document_request, $uploaded_file);

            $this->fileStorageService->writeFromLocal($uploaded_file->getRealPath(), $document_file);

            $files_documents[] = $document_file;
        }

        $this->file_repository->documentManager->flush();

        return $files_documents;
    }
}