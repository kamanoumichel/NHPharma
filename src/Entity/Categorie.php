<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    use SlugTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_cat = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $categoryorders = null;

    /** #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: SousCategorie::class, orphanRemoval: true)] private Collection $sousCategories; */

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')]
    private ?self $parent = null;
   

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $categories;
    
    public function __toString()
    {
        return $this->nom_cat;
    }
    public function __construct()
    {
        // $this->sousCategories = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCat(): ?string
    {
        return $this->nom_cat;
    }
    public function getCategoryorders():?int
    {
        return $this->categoryorders;
    }

    public function setCategoryorders(int $categoryorders): self
    {
        $this->categoryorders=$categoryorders;
        return $this;
    }
    public function setNomCat(string $nom_cat): static
    {
        $this->nom_cat = $nom_cat;

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

   

  

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setParent($this);
        }

        return $this;
    }

    public function removeCategory(self $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getParent() === $this) {
                $category->setParent(null);
            }
        }

        return $this;
    }
}
