<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"article:read"}},
 *     denormalizationContext={"groups"={"article:write"}}
 * )
 * @ApiFilter(OrderFilter::class,  properties={"publishedAt"})
 * @ApiFilter(SearchFilter::class, properties={"tags.name": "exact"})
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable()
 */
// @ ORM\HasLifecycleCallbacks()
class Article
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"article:read"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, options={"default":"", "comment":"Title of the article"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     *
     * @Groups({"article:read", "article:write"})
     */
    private string $title;

    /**
     * @ORM\Column(type="text", options={"default":"", "comment":"Content of the article"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="65535")
     *
     * @Groups({"article:read", "article:write"})
     */
    private string $content;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="articles")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"article:read", "article:write"})
     * @ApiSubresource(maxDepth=1)
     * @MaxDepth(1)
     */
    private ?Status $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"Default", "article:read"})
     *
     * @Gedmo\Timestampable(on="change", field="status.name", value="PUBLISH")
     */
    private ?\DateTimeInterface $publishedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="articles")
     *
     * @Groups({"article:read", "article:write"})
     * @ApiSubresource()
     * @ MaxDepth(1) maxDepth=1
     */
    private Collection $tags;

    public function __construct()
    {
        $this->title = '';
        $this->content = '';
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }
}
