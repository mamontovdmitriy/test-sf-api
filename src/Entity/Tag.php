<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable()
 */
class Tag
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"tag:read", "article:read"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=32, options={"comment":"Name of tag of the article"})
     *
     * @Assert\NotBlank()
     *
     * @Groups({"tag:read", "tag:write", "article:read"})
     */
    private ?string $name;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="tags")
     */
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

//    public function addArticle(Article $article): self
//    {
//        if (!$this->articles->contains($article)) {
//            $this->articles[] = $article;
//            $article->addTag($this);
//        }
//
//        return $this;
//    }
//
//    public function removeArticle(Article $article): self
//    {
//        if ($this->articles->contains($article)) {
//            $this->articles->removeElement($article);
//            $article->removeTag($this);
//        }
//
//        return $this;
//    }
}
