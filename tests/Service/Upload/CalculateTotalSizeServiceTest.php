<?php declare( strict_types = 1 );

namespace App\Service\Tests\Upload;

use App\Service\Upload\CalculateTotalSizeService;
use App\Tests\CommonTestMethods;
use PHPUnit\Framework\TestCase;

class CalculateTotalSizeServiceTest extends TestCase
{
    public function test(): void
    {
        $serivice = new CalculateTotalSizeService;

        $files_count = 10;

        $expected_size = 1024 * 1024 * $files_count;

        $each_file_size = $expected_size / $files_count;

        $files = CommonTestMethods::genRandomUploadedFiles($files_count, $each_file_size);

        $this->assertEquals($expected_size, $serivice->getSize($files));
    }
}