<?php declare( strict_types = 1 );

namespace App\EventListener\Document;

use App\Document\File;
use App\Service\FileStorageService;

class FileListener
{

    public function __construct(private FileStorageService $fileStorageService)
    {}

    public function postRemove(File $document_file):void
    {
        $this->fileStorageService->delete($document_file);
    }
}