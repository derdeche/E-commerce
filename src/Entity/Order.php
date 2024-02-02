<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // #[ORM\Column]
    // private ?int $quantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $taxe = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $delivery_address = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $orderCost = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $orderCostHt = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderDetail::class, cascade: ['persist', 'remove'])]
    private Collection $orderDetails;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

 

    public function getTaxe(): ?int
    {
        return $this->taxe;
    }

    public function setTaxe(?int $taxe): static
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->delivery_address;
    }

    public function setDeliveryAddress(?string $delivery_address): static
    {
        $this->delivery_address = $delivery_address;

        return $this;
    }

    public function setUserIdToZero()
    {
        $this->user = null;
        $this->id = 0;
    }

    public function getOrderCost(): ?string
    {
        return $this->orderCost;
    }

    public function setOrderCost(string $orderCost): static
    {
        $this->orderCost = $orderCost;

        return $this;
    }

    public function getOrderCostHt(): ?string
    {
        return $this->orderCostHt;
    }

    public function setOrderCostHt(?string $orderCostHt): static
    {
        $this->orderCostHt = $orderCostHt;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetail $orderDetail): static
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setOrder($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): static
    {
        $this->orderDetails->removeElement($orderDetail);

        return $this;
    }
}
