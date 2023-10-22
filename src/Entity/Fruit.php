<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 *
 * @ApiFilter(SearchFilter::class, properties={"name": "ipartial","family": "ipartial", "isFavourite": "exact"})
 *
 * @ORM\Entity(repositoryClass=FruitRepository::class)
 *
 * @ORM\HasLifecycleCallbacks
 */
class Fruit
{
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $family;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $fruitOrder;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $genus;

    /**
     * @ORM\Column(type="json")
     */
    private $nutritions = [];

    /**
     * @ORM\Column(name="is_favourite", type="boolean", options={"default":0})
     */
    private $isFavourite;

    /**
     * @ORM\column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\column(name="updated_at", type="datetime")
     */
    private $updatedAt;

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

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(?string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getFruitOrder(): ?string
    {
        return $this->fruitOrder;
    }

    public function setFruitOrder(?string $fruitOrder): self
    {
        $this->fruitOrder = $fruitOrder;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(?string $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    public function getNutritions(): ?array
    {
        return $this->nutritions;
    }

    public function setNutritions(array $nutritions): self
    {
        $this->nutritions = $nutritions;

        return $this;
    }

    public function getIsFavourite(): bool
    {
        return $this->isFavourite;
    }

    public function setIsFavourite(bool $isFavourite): self
    {
        $this->isFavourite = $isFavourite;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Gets triggered only on insert.
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * Gets triggered every time on update.
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
