<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"}
 * )
 * @ORM\Entity()
 */
class Status
{
    public const DRAFT = 1;
    public const REVIEW = 2;
    public const PUBLISH = 3;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     *
     * @Groups({"status:read", "article:read"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=32, options={"comment":"Name of the article status"})
     *
     * @Groups({"status:read", "article:read"})
     */
    private ?string $name;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="status")
     *
     * @Groups({"status:read"})
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

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
}
