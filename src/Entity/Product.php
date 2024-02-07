<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $additionalInfos = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $moreDescription = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\Column(nullable: true)]
    private ?int $soldePrice = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brand = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBestSeller = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isSpecialOffer = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $imageUrls = [];

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Slider::class, mappedBy: 'sliders')]
    private Collection $sliders;

    #[ORM\Column(nullable: true)]
    private ?bool $isNewCollection = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPreference = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

  
        /**
     * @ORM\OneToMany(targetEntity=OrderDetail::class, mappedBy="product")
     */
    private Collection $orderDetails;


    public function __construct()
    {
        $this->sliders = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
    }

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

    public function getMoreDescription(): ?string
    {
        return $this->moreDescription;
    }

    public function setMoreDescription(?string $moreDescription): static
    {
        $this->moreDescription = $moreDescription;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSoldePrice(): ?int
    {
        return $this->soldePrice;
    }

    public function setSoldePrice(?int $soldePrice): static
    {
        $this->soldePrice = $soldePrice;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function isIsBestSeller(): ?bool
    {
        return $this->isBestSeller;
    }

    public function setIsBestSeller(?bool $isBestSeller): static
    {
        $this->isBestSeller = $isBestSeller;

        return $this;
    }

    public function isIsSpecialOffer(): ?bool
    {
        return $this->isSpecialOffer;
    }

    public function setIsSpecialOffer(?bool $isSpecialOffer): static
    {
        $this->isSpecialOffer = $isSpecialOffer;

        return $this;
    }

    public function getImageUrls(): array
    {
        return $this->imageUrls;
    }

    public function setImageUrls(array $imageUrls): static
    {
        $this->imageUrls = $imageUrls;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAdditionalInfos(): ?string
    {
        return $this->additionalInfos;
    }

    public function setAdditionalInfos(?string $additionalInfos): static
    {
        $this->additionalInfos = $additionalInfos;

        return $this;
    }

    /**
     * @return Collection<int, Slider>
     */
    public function getSliders(): Collection
    {
        return $this->sliders;
    }

    public function addSlider(Slider $slider): static
    {
        if (!$this->sliders->contains($slider)) {
            $this->sliders->add($slider);
            $slider->addSlider($this);
        }

        return $this;
    }

    public function removeSlider(Slider $slider): static
    {
        if ($this->sliders->removeElement($slider)) {
            $slider->removeSlider($this);
        }

        return $this;
    }

    public function isIsNewCollection(): ?bool
    {
        return $this->isNewCollection;
    }

    public function setIsNewCollection(?bool $isNewCollection): static
    {
        $this->isNewCollection = $isNewCollection;

        return $this;
    }

    public function isIsPreference(): ?bool
    {
        return $this->isPreference;
    }

    public function setIsPreference(?bool $isPreference): static
    {
        $this->isPreference = $isPreference;

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

   
}