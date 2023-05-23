<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\Category\Category;
use App\Repository\Product\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255), NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255), NotBlank]
    private ?string $description = null;

    #[ORM\Column, NotBlank]
    private ?int $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $discount_price = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $discount_period_starts_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $discount_period_ends_at = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'products')]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscountPrice(): ?int
    {
        return $this->discount_price;
    }

    public function setDiscountPrice(?int $discount_price): self
    {
        $this->discount_price = $discount_price;

        return $this;
    }

    public function getDiscountPeriodStartsAt(): ?\DateTimeImmutable
    {
        return $this->discount_period_starts_at;
    }

    public function setDiscountPeriodStartsAt(?\DateTimeImmutable $discount_period_starts_at): self
    {
        $this->discount_period_starts_at = $discount_period_starts_at;

        return $this;
    }

    public function getDiscountPeriodEndsAt(): ?\DateTimeImmutable
    {
        return $this->discount_period_ends_at;
    }

    public function setDiscountPeriodEndsAt(?\DateTimeImmutable $discount_period_ends_at): self
    {
        $this->discount_period_ends_at = $discount_period_ends_at;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduct($this);
        }

        return $this;
    }
}
