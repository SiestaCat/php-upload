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
            $files[] = new UploadedFile
            (
                RandomFileGenerator::generate(null, null, $each_file_size),
                strval(u(self::getFaker()->words(5,true))->snake()) . '.' . self::getFaker()->fileExtension()
            );
        }

        return $files;
    }

    public static function getFaker():Generator
    {
        if(self::$faker === null) self::$faker = \Faker\Factory::create();

        return self::$faker;
    }
}