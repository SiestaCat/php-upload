<?php declare( strict_types = 1 );

namespace App\Tests;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Faker\Generator;
use Siestacat\RandomFileGenerator\RandomFileGenerator;
use function Symfony\Component\String\u;

final class CommonTestMethods
{

    private static ?Generator $faker = null;

    /**
     * @return UploadedFile[]
     */
    public static function genRandomUploadedFiles(int $count, int $each_file_size):array
    {
        $files = [];

        for($i=1;$i<=$count;$i++)
        {
            $files[] = self::genRandomUploadedFile($each_file_size);
        }

        return $files;
    }

    public static function genRandomUploadedFile(int $size):UploadedFile
    {
        return new UploadedFile
        (
            RandomFileGenerator::generate(null, null, $size),
            strval(u(self::getFaker()->words(5,true))->snake()) . '.' . self::getFaker()->fileExtension()
        );
    }

    public static function getFaker():Generator
    {
        if(self::$faker === null) self::$faker = \Faker\Factory::create();

        return self::$faker;
    }
}