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
}