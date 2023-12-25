<?php declare( strict_types = 1 );

namespace App\Service\Tests\Api;

use App\Service\Api\Exception\ParamNotExistsException;
use PHPUnit\Framework\TestCase;
use App\Service\Api\RequestApiService;

class RequestApiServiceTest extends TestCase
{
    /**
     * @dataProvider jsonProvider
     */
    public function test(\stdClass $json):void
    {
        $service = new RequestApiService;

        $document = $service->getDocumentFromJson($json);

        foreach($json as $property => $value)
        {
            $this->assertEquals($value, $document->{$property});
        }

        if(count((array) $json) === 0) $this->assertTrue(true);
    }

    public function testNotExistentParam():void
    {
        $this->expectException(ParamNotExistsException::class);
        (new RequestApiService)->getDocumentFromJson((object) ['param_not_exists' => 123], ['param_not_exists']);
    }

    public function testNotIntParam():void
    {
        $param_name = RequestApiService::INT_PARAMS_EXPECTED[0];
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(sprintf('Param %s should be int', $param_name));
        (new RequestApiService)->getDocumentFromJson((object) [$param_name => 'abcd']);
    }

    public function jsonProvider():array
    {
        $jsons = [];

        foreach(\Siestacat\BoolPermutations\Permutate::get(count(RequestApiService::INT_PARAMS_EXPECTED)) as $bool_arr)
        {
            $json = new \stdClass;
            foreach(RequestApiService::INT_PARAMS_EXPECTED as $index => $param_name)
            {
                if($bool_arr[$index])
                {
                    $json->{$param_name} = rand(1000,10000);
                }

            }
            $jsons[] = [$json];
        }
        return $jsons;
    }
}