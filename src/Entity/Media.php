<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Controller\CreateMediaAction;
use App\Entity\Article;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"media:read"}
 *     },
 *     collectionOperations={
 *         "post"={
 *             "controller"=CreateMediaAction::class,
 *             "deserialize"=false,
 *             "validation_groups"={"Default", "media:create"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *         "get"
 *     },
 *     itemOperations={
 *         "get"
 *     }
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\Entity()
 * @Vich\Uploadable()
 */
class Media
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @Groups({"media:read"})
     * @ApiProperty(iri="http://schema.org/contentUrl")
     */
    private string $url;

    /**
     * @Assert\NotNull(groups={"media:create"})
     *
     * @Vich\UploadableField(mapping="media_photo", fileNameProperty="path")
     */
    private ?File $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $path;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="photos")
     *
     * @Groups({"media:read"})
     */
    private ?Article $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
