<?php declare( strict_types = 1 );

namespace App\Document;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\RequestRepository;

#[MongoDB\Document(collection: 'upload_api_request', repositoryClass: RequestRepository::class)]
#[MongoDB\HasLifecycleCallbacks]
#[MongoDB\Index(['date_created'])]
class Request
{
    #[MongoDB\Id]
    public string $id;

    #[MongoDB\Field(type: 'string')]
    public string $upload_token;

    #[MongoDB\Field(type: 'date_immutable')]
    public ?DateTimeImmutable $date_created = null;

    #[MongoDB\PrePersist]
    public function onPrePersist()
    {
        $this->date_created = new DateTimeImmutable;
        $this->upload_token = \IcyApril\CryptoLib::randomString(64);
    }
}
