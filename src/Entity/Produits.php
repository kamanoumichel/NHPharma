<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
#[Vich\Uploadable]
class Produits
{
    use SlugTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_pro = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $detail = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_per = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $sous_categorie;
    


    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Commande::class, orphanRemoval: true)]
    private Collection $commandes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attachment = null;

    #[Vich\UploadableField(mapping:'products', fileNameProperty: 'attachment')]
    private ?File $attachmentFile = null;
    public function __toString()
    {
        return $this->nom_pro.'-'.
                $this->detail.'-'.
                $this->prix.' XAF';
    }
    public function __construct()
    {
       
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPro(): ?string
    {
        return $this->nom_pro;
    }

    public function setNomPro(string $nom_pro): static
    {
        $this->nom_pro = $nom_pro;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): static
    {
        $this->detail = $detail;

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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDatePer(): ?\DateTimeInterface
    {
        return $this->date_per;
    }

    public function setDatePer(\DateTimeInterface $date_per): static
    {
        $this->date_per = $date_per;

        return $this;
    }

    public function getSousCategorie(): ?Categorie
    {
        return $this->sous_categorie;
    }

    public function setSousCategorie(?Categorie $sous_categorie): static
    {
        $this->sous_categorie = $sous_categorie;

        return $this;
    }
 

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getProduit() === $this) {
                $commande->setProduit(null);
            }
        }

        return $this;
    }

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(?string $attachment): static
    {
        $this->attachment = $attachment;

        return $this;
    }
    public function setAttachmentFile(?File $attachmentFile = null): void
    {
        $this->attachmentFile = $attachmentFile;

        
    }

    public function getAttachmentFile(): ?File
    {
        return $this->attachmentFile;
    }
}
