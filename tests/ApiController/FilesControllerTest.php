<?php declare( strict_types = 1 );

namespace App\Tests\ApiController;

use App\Document\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\FileRepository;
use App\Repository\RequestRepository;
use App\Tests\CommonTestMethods;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FilesControllerTest extends WebTestCase
{

    private RequestRepository $request_repository;

    private FileRepository $file_repository;

    private KernelBrowser $client;

    private ContainerInterface $container;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->container = $this->client->getContainer();

        $this->request_repository = $this->container->get('test.' . RequestRepository::class);
        $this->file_repository = $this->container->get('test.' . FileRepository::class);
    }

    public function test():void
    {
        $document_request = $this->createRequestAndFiles();

        $this->client->followRedirects(true);

        $this->client->request('GET', '/api/files/' . $document_request->upload_token, [], [], [
            'HTTP_Content-Type' => 'application/json',
            'HTTP_Authorization' => sprintf('Basic %s', $this->container->getParameter('api_token'))
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $str_response = $this->client->getResponse()->getContent();

        $json_response = json_decode($str_response);

        $is_object = is_object($json_response);

        $this->assertTrue($is_object, sprintf('JSON decode from str: %s', $str_response));

        $this->assertTrue(property_exists($json_response, 'success'), 'Check if property "success" exists in json');
        $this->assertTrue($json_response->success === true, 'Check if property "success" is true');
        $this->assertTrue(property_exists($json_response, 'files'), 'Check if property "files" exists in json');
        $this->assertTrue(is_array($json_response->files), 'Check if property "files" is array');
    }

    private function createRequestAndFiles():Request
    {
        $document_request = new Request;

        $document_request->uploaded = true;

        $files_documents = [];

        for($i=1;$i<=10;$i++)
        {
            $files_documents[] = $this->file_repository->create($document_request, CommonTestMethods::genRandomUploadedFile(1024*1024));
        }

        $this->request_repository->documentManager->persist($document_request);

        $this->file_repository->documentManager->flush();

        return $document_request;
    }
}