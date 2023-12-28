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
    public function __construct(public DocumentManager $documentManager, ManagerRegistry $registry, private string $hash_algo, private RequestRepository $requestRepository)
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
        return $this->documentManager->createQueryBuilder(File::class)
            ->field('request')->equals($this->requestRepository->getAttached($document_request))
            ->count()
            ->getQuery()
            ->execute();
    }

    /**
     * @return File[]
     */
    public function getListByRequest(Request $document_request):array
    {
        return $this->documentManager->createQueryBuilder(File::class)
            ->field('request')->equals($this->requestRepository->getAttached($document_request))
            ->getQuery()
            ->execute();
    }
}