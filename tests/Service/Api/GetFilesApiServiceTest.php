<?php declare( strict_types = 1 );

namespace App\Service\Tests\Api;

use App\Document\File;
use App\Document\Request;
use App\Repository\FileRepository;
use App\Repository\RequestRepository;
use App\Service\Api\GetFilesApiService;
use App\Tests\CommonTestMethods;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetFilesApiServiceTest extends KernelTestCase
{
    private GetFilesApiService $service;

    private RequestRepository $request_repository;

    private FileRepository $file_repository;

    public function setUp(): void
    {
        self::bootKernel();

        $container = self::$kernel->getContainer();

        $this->service = $container->get('test.' . GetFilesApiService::class);
        $this->request_repository = $container->get('test.' . RequestRepository::class);
        $this->file_repository = $container->get('test.' . FileRepository::class);
    }

    public function test():void
    {
        $document_request = new Request;

        $files_documents = [];

        for($i=1;$i<=10;$i++)
        {
            $files_documents[] = $this->file_repository->create($document_request, CommonTestMethods::genRandomUploadedFile(1024*1024));
        }

        $this->request_repository->documentManager->persist($document_request);

        $this->file_repository->documentManager->flush();

        $files = $this->service->getFiles($document_request);

        foreach($files_documents as $index => $document_file)
        {
            $file_object = $files[$index];

            $this->assertTrue(get_class($file_object) === \stdClass::class);

            $this->assertEquals($document_file->filename, $file_object->filename);
            $this->assertEquals($document_file->hash, $file_object->hash);
            $this->assertEquals($document_file->size, $file_object->size);
            $this->assertEquals($document_file->mime, $file_object->mime);
        }
    }
}