<?php declare( strict_types = 1 );

namespace App\Service\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSizeValidatorService
{
    public function check_size(UploadedFile $file, int $max_size): bool
    {
        return $file->getSize() <= $max_size;
    }
}