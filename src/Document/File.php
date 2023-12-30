<?php declare( strict_types = 1 );

namespace App\Document;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\FileRepository;

#[MongoDB\Document(collection: 'upload_api_file', repositoryClass: FileRepository::class)]
#[MongoDB\HasLifecycleCallbacks]
#[MongoDB\Index(['request'])]
class File
{
    #[MongoDB\Id]
    public string $id;

    #[MongoDB\ReferenceOne(targetDocument: Request::class)]
    public Request $request;

    #[MongoDB\Field(type: 'string')]
    public string $filename;

    #[MongoDB\Field(type: 'string')]
    public string $hash;

    #[MongoDB\Field(type: 'int')]
    public int $size;

    #[MongoDB\Field(type: 'string')]
    public ?string $mime;
}
