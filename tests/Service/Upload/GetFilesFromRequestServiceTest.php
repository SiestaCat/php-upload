<?php declare( strict_types = 1 );

namespace App\Service\Tests\Upload;

use App\Service\Upload\GetFilesFromRequestService;
use App\Tests\CommonTestMethods;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;

class GetFilesFromRequestServiceTest extends TestCase
{
    public function test(): void
    {
        $serivice = new GetFilesFromRequestService;

        $files = CommonTestMethods::genRandomUploadedFiles(10, 1024 * 1024);

        $request = $this->createMock(Request::class);

        $request->files = new FileBag([GetFilesFromRequestService::FILES_INPUT_NAME => $files]);

        $this->assertEquals(count($files), count($serivice->get($request)));
    }
}