<?php declare( strict_types = 1 );

namespace App\Tests\ApiController;

use App\Document\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\FileRepository;
use App\Repository\RequestRepository;
use App\Tests\CommonTestMethods;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StatusControllerTest extends WebTestCase
{

    private RequestRepository $request_repository;

    private KernelBrowser $client;

    private ContainerInterface $container;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->container = $this->client->getContainer();

        $this->request_repository = $this->container->get('test.' . RequestRepository::class);
    }

    public function testUploadedTrue():void
    {
        $document_request = $this->createRequest(true);

        $this->client->followRedirects(true);

        $this->client->request('GET', '/api/status/' . $document_request->upload_token, [], [], [
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
        $this->assertTrue(property_exists($json_response, 'uploaded'), 'Check if property "uploaded" exists in json');
        $this->assertTrue($json_response->uploaded, 'Check if property "uploaded" is true');
    }

    public function testUploadedFalse():void
    {
        $document_request = $this->createRequest(false);

        $this->client->followRedirects(true);

        $this->client->request('GET', '/api/status/' . $document_request->upload_token, [], [], [
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
        $this->assertTrue(property_exists($json_response, 'uploaded'), 'Check if property "uploaded" exists in json');
        $this->assertFalse($json_response->uploaded, 'Check if property "uploaded" is false');
    }

    private function createRequest(bool $is_uploaded):Request
    {
        $document_request = new Request;

        if($is_uploaded) $document_request->uploaded = true;

        $this->request_repository->documentManager->persist($document_request);

        $this->request_repository->documentManager->flush();

        return $document_request;
    }
}