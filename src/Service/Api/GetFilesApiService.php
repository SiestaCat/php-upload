<?php declare( strict_types = 1 );

namespace App\Service\Api;

use App\Document\Request;
use App\Repository\FileRepository;

class GetFilesApiService
{

    public function __construct(private FileRepository $fileRepository)
    {}

    /**
     * @return \stdClass[]
     */
    public function getFiles(Request $document_request):array
    {
        $files = [];

        foreach($this->fileRepository->getListByRequest($document_request) as $document_file)
        {
            $files[] = (object)
            [
                'filename' => $document_file->filename,
                'hash' => $document_file->hash,
                'size' => $document_file->size,
                'mime' => $document_file->mime
            ];
        }

        return $files;
    }
}