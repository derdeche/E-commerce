<?php

namespace App\Entity;

use App\Repository\SliderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SliderRepository::class)]
class Slider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    

    #[UrlField('button_link')]
    #[ORM\Column(length: 255)]
    private ?string $button_link = null;

    
    

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'sliders')]
    private Collection $sliders;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

   

    public function getButtonLink(): ?string
    {
        return $this->button_link;
    }

    public function setButtonLink(string $button_link): static
    {
        $this->button_link = $button_link;

        return $this;
    }

  

 

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function __construct()
    {
    //    $this->created_at = new \DateTimeImmutable();
   
        $this->setCreatedAt(new \DateTimeImmutable());
        
        $this->sliders = new ArrayCollection();
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return Collection<int, product>
     */
    public function getSliders(): Collection
    {
        return $this->sliders;
    }

    public function addSlider(product $slider): static
    {
        if (!$this->sliders->contains($slider)) {
            $this->sliders->add($slider);
        }

        return $this;
    }

    public function removeSlider(product $slider): static
    {
        $this->sliders->removeElement($slider);

        return $this;
    }

   

}
