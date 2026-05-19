<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:list', 'product:detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:list', 'product:detail'])]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    #[Groups(['product:list', 'product:detail'])]
    private ?string $brand = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['product:detail'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['product:list', 'product:detail'])]
    private ?string $price = null;

    #[ORM\Column]
    #[Groups(['product:detail'])]
    private ?int $stock = null;

    #[ORM\Column(length: 50)]
    #[Groups(['product:list', 'product:detail'])]
    private ?string $color = null;

    #[ORM\Column(length: 50)]
    #[Groups(['product:list', 'product:detail'])]
    private ?string $storage = null;

    #[ORM\Column]
    #[Groups(['product:detail'])]
    private ?float $screenSize = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(string $storage): static
    {
        $this->storage = $storage;

        return $this;
    }

    public function getScreenSize(): ?float
    {
        return $this->screenSize;
    }

    public function setScreenSize(float $screenSize): static
    {
        $this->screenSize = $screenSize;

        return $this;
    }
}
