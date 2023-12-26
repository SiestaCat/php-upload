<?php declare( strict_types = 1 );

namespace App\Service\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class CalculateTotalSizeService
{

    /**
     * @param UploadedFile[] $files 
     */
    public function getSize(array $files): int
    {
        $size = 0;

        foreach($files as $file)
        {
            if($file->getError() === UPLOAD_ERR_OK)
            {
                $size += $file->getSize();
            }
        }

        return $size;
    }
}