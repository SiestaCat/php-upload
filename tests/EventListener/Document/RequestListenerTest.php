<?php declare( strict_types = 1 );

namespace App\EventListener\Tests\Document;

use App\Document\Request;
use App\EventListener\Document\Exception\TokenMinLengthException;
use PHPUnit\Framework\TestCase;
use App\EventListener\Document\RequestListener;

class RequestListenerTest extends TestCase
{
    /**
     * @dataProvider postPersistProvider
     */
    public function testPostPersist(?int $default_max_bytes, ?int $default_max_bytes_per_file, ?int $default_max_files): void
    {
        $token_length = 12;

        $document_request = new Request;
        
        (new RequestListener($token_length, $default_max_bytes?:0, $default_max_bytes_per_file?:0, $default_max_files?:0))
        ->postPersist($document_request);

        $this->assertEquals($token_length, strlen($document_request->upload_token));

        if($default_max_bytes) $this->assertEquals($default_max_bytes, $document_request->max_bytes);
        if($default_max_bytes_per_file) $this->assertEquals($default_max_bytes_per_file, $document_request->max_bytes_per_file);
        if($default_max_files) $this->assertEquals($default_max_files, $document_request->max_files);
    
    }

    public function testTokenMinLengthException(): void
    {
        $this->expectException(TokenMinLengthException::class);

        (new RequestListener(RequestListener::TOKEN_LENGTH_MIN - 1, 0, 0, 0))->checkTokenLenght();
    }

    public function postPersistProvider()
    {
        $arr = [];

        foreach(\Siestacat\BoolPermutations\Permutate::get(3) as $bool_arr)
        {
            $a = [];
            foreach($bool_arr as $bool)
            {
                $a[] = $bool ? rand(1000,10000) : null;
            }
            $arr[] = $a;
        }
        return $arr;
    }
}