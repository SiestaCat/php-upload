<?php declare( strict_types = 1 );

namespace App\Document;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\RequestRepository;
use App\EventListener\Document\RequestListener;
use Siestacat\DoctrineOdmEventListener\EventListenerAttribute;

#[MongoDB\Document(collection: 'upload_api_request', repositoryClass: RequestRepository::class)]
#[MongoDB\HasLifecycleCallbacks]
#[MongoDB\Index(['date_created'])]
#[EventListenerAttribute([RequestListener::class])]
class Request
{
    #[MongoDB\Id]
    public string $id;

    #[MongoDB\Field(type: 'string')]
    public string $upload_token;

    #[MongoDB\Field(type: 'int')]
    public int $max_bytes;

    #[MongoDB\Field(type: 'int')]
    public int $max_bytes_per_file;

    #[MongoDB\Field(type: 'int')]
    public int $max_files;

    #[MongoDB\Field(type: 'date_immutable')]
    public ?DateTimeImmutable $date_created = null;

    #[MongoDB\PrePersist]
    public function onPrePersist()
    {
        $this->date_created = new DateTimeImmutable;
    }
}
