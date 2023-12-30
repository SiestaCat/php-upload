<?php declare( strict_types = 1 );

namespace App\Repository;

use App\Document\Request;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class RequestRepository extends ServiceDocumentRepository
{
    public function __construct(public DocumentManager $documentManager, ManagerRegistry $registry)
    {
        parent::__construct($registry, Request::class);
    }

    public function getByUploadByToken(string $upload_token):?Request
    {
        return $this->findOneBy(['upload_token' => $upload_token]);
    }

    public function getPendingByUploadByToken(string $upload_token):?Request
    {
        return $this->findOneBy(['upload_token' => $upload_token, 'uploaded' => false]);
    }

    public function setUploaded(Request $document_request):void
    {
        $this->documentManager->createQueryBuilder(Request::class)
        ->findAndUpdate()
        ->field('id')->equals($document_request->id)
        ->field('uploaded')->set(true)
        ->getQuery()
        ->execute();
    }

    public function getAttached(Request $document_request):Request
    {
        return $this->find($document_request->id);
    }
}