<?php declare( strict_types = 1 );

namespace App\Tests\ApiController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RequestControllerTest extends WebTestCase
{
    public function test():void
    {
        $client = static::createClient();

        $client->followRedirects(true);

        $container = $client->getContainer();

        $client->request('POST', '/api/request/', [], [], [
            'HTTP_Content-Type' => 'application/json',
            'HTTP_Authorization' => sprintf('Basic %s', $container->getParameter('api_token'))
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $str_response = $client->getResponse()->getContent();

        $json_response = json_decode($str_response);

        $is_object = is_object($json_response);

        $this->assertTrue($is_object, sprintf('JSON decode from str: %s', $str_response));

        $this->assertTrue(property_exists($json_response, 'upload_token'), 'Check if property "upload_token" exists in json');
    }
}