<?php declare( strict_types = 1 );

namespace App\Tests\Service;

use App\Document\File;
use App\Document\Request;
use App\Service\FileStorageService;
use App\Tests\CommonTestMethods;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FileStorageServiceTest extends KernelTestCase
{

    const FILE_SIZE = 1024 * 1024 * 10; //10 MB

    private ?FileStorageService $service = null;

    private ?string $hash_algo = null;

    public function setUp(): void
    {
        self::bootKernel();

        $container = self::$kernel->getContainer();

        $this->hash_algo = $container->getParameter('hash_algo');
    }

    public function test_writeFromLocal():void
    {
        $this->delete($this->writeFile());
    }

    public function test_getStream():void
    {
        $document_file = $this->writeFile();

        $stream = $this->getService()->getStream($document_file);

        $this->assertEquals($document_file->hash, hash($this->hash_algo, stream_get_contents($stream)));

        $this->delete($document_file);
    }

    private function getService():FileStorageService
    {
        if($this->service === null)
        {
            $this->service = new FileStorageService(new \League\Flysystem\Filesystem(new \League\Flysystem\InMemory\InMemoryFilesystemAdapter()), $this->hash_algo);
        }

        return $this->service;
    }

    private function getDocumentFile(string $local_path):File
    {
        $document_request = new Request;

        $document_request->upload_token = \IcyApril\CryptoLib::randomString(8);

        $document_file = new File;

        $document_file->request = $document_request;

        $document_file->hash = hash_file($this->hash_algo, $local_path);

        return $document_file;
    }

    private function writeFile():File
    {
        $local_path = CommonTestMethods::genRandomUploadedFile(self::FILE_SIZE)->getRealPath();

        $document_file = $this->getDocumentFile($local_path);

        $this->assertTrue($this->getService()->writeFromLocal($local_path, $document_file));

        return $document_file;
    }

    private function delete(File $document_file):void
    {
        $this->assertTrue($this->getService()->delete($document_file));
    }
}