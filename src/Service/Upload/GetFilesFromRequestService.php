<?php declare( strict_types = 1 );

namespace App\Service\Upload;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GetFilesFromRequestService
{

    CONST FILES_INPUT_NAME = 'files';

    /**
     * @return UploadedFile[] 
     */
    public function get(Request $request): array
    {
        $files = $request->files ? $request->files->all() : [];

        return array_key_exists(self::FILES_INPUT_NAME, $files) ? $files[self::FILES_INPUT_NAME] : [];
    }
}