<?php declare( strict_types = 1 );

namespace App\Tests\ApiController;

use App\Document\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\RequestRepository;
use App\Tests\CommonTestMethods;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;

class UploadControllerTest extends WebTestCase
{
    private RequestRepository $request_repository;

    private KernelBrowser $client;

    private ContainerInterface $container;

    private ?string $upload_webhook_base_url = null;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->container = $this->client->getContainer();

        $this->request_repository = $this->container->get('test.' . RequestRepository::class);

        $this->upload_webhook_base_url = $this->container->getParameter('webhook_upload_base_url');
    }

    public function test():void
    {
        $document_request = new Request;

        $document_request->webhook_upload = $this->upload_webhook_base_url . '/set';

        $this->request_repository->documentManager->persist($document_request);
        $this->request_repository->documentManager->flush();

        $files = [];

        foreach(CommonTestMethods::genRandomUploadedFiles(10, 1024*2014) as $uploaded_file)
        {
            $files[] = $uploaded_file;
        }

        $this->client->request('POST', '/upload/' . $document_request->upload_token, [], ['files' => $files]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $str_response = $this->client->getResponse()->getContent();

        $json_response = json_decode($str_response);

        $is_object = is_object($json_response);

        $this->assertTrue($is_object, sprintf('JSON decode from str: %s', $str_response));

        $this->assertTrue(property_exists($json_response, 'success'), 'Check if property "success" exists in json');

        $this->assertTrue($json_response->success);

        $this->assertTrue(boolval(file_get_contents($this->upload_webhook_base_url . '/get')), 'Webhook sended');

    }

    
}