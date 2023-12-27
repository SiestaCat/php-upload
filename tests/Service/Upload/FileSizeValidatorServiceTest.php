<?php declare( strict_types = 1 );

namespace App\Service\Tests\Upload;

use App\Service\Upload\FileSizeValidatorService;
use App\Tests\CommonTestMethods;
use PHPUnit\Framework\TestCase;


class FileSizeValidatorServiceTest extends TestCase
{
    public function test(): void
    {
        $serivice = new FileSizeValidatorService;

        $file_size = 1024 * 1024;

        $file = CommonTestMethods::genRandomUploadedFile($file_size);

        $this->assertTrue($serivice->check_size($file, $file_size));

        $this->assertTrue($serivice->check_size($file, $file_size * 2));

        $this->assertFalse($serivice->check_size($file, $file_size - 1));
        $this->assertFalse($serivice->check_size($file, intval($file_size / 2)));
    }
}