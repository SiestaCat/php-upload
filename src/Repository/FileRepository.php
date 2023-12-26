<?php declare( strict_types = 1 );

namespace App\Repository;

use App\Document\File;
use App\Document\Request;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileRepository extends ServiceDocumentRepository
{
    public function __construct(public DocumentManager $documentManager, ManagerRegistry $registry, private string $hash_algo)
    {
        parent::__construct($registry, File::class);
    }

    public function create(Request $document_request, UploadedFile $uploaded_file):File
    {
        $file = new File;
        $file->request = $document_request;
        $file->filename = $uploaded_file->getClientOriginalName();
        $file->hash = hash_file($this->hash_algo, $uploaded_file->getRealPath());
        $file->size = $uploaded_file->getSize();
        $file->mime = $uploaded_file->getMimeType();

        $this->documentManager->persist($file);

        return $file;
    }

    public function getCountByRequest(Request $document_request):int
    {

        $attached_document_request = $this->documentManager->getRepository(Request::class)->find($document_request->id);

        return $this->documentManager->createQueryBuilder(File::class)
            ->field('request')->equals($attached_document_request)
            ->count()
            ->getQuery()
            ->execute();

    }
}