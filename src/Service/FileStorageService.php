<?php declare( strict_types = 1 );

namespace App\Service;

use App\Document\File;
use League\Flysystem\FilesystemOperator;

class FileStorageService
{
    public function __construct(private FilesystemOperator $defaultStorage, private string $hash_algo)
    {}

    public function writeFromLocal(string $local_file_path, File $document_file):bool
    {
        $this->defaultStorage->writeStream($this->getRelativePath($document_file), fopen($local_file_path, 'r'));

        return $this->exists($document_file);
    }

    /**
     * @return resource
     */
    public function getStream(File $document_file)
    {
        return $this->defaultStorage->readStream($this->getRelativePath($document_file));
    }

    public function exists(File $document_file):bool
    {
        return $this->defaultStorage->fileExists($this->getRelativePath($document_file));
    }

    public function delete(File $document_file):bool
    {
        $this->defaultStorage->delete($this->getRelativePath($document_file));

        return !$this->exists($document_file);
    }  

    private static function getRelativePath(File $document_file):string
    {
        return $document_file->request->upload_token . DIRECTORY_SEPARATOR . $document_file->hash;
    }
}