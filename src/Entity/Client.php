<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_client = null;

    #[ORM\Column(length: 255)]
    private ?string $adress_liv = null;

    #[ORM\Column(length: 255)]
    private ?string $e_mail = null;

    #[ORM\Column(length: 255)]
    private ?int $tel_what = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $paniers;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?string
    {
        return $this->nom_client;
    }

    public function setNomClient(string $nom_client): static
    {
        $this->nom_client = $nom_client;

        return $this;
    }

    public function getAdressLiv(): ?string
    {
        return $this->adress_liv;
    }

    public function setAdressLiv(string $adress_liv): static
    {
        $this->adress_liv = $adress_liv;

        return $this;
    }

    public function getEMail(): ?string
    {
        return $this->e_mail;
    }

    public function setEMail(string $e_mail): static
    {
        $this->e_mail = $e_mail;

        return $this;
    }

    public function getTelWhat(): ?string
    {
        return $this->tel_what;
    }

    public function setTelWhat(string $tel_what): static
    {
        $this->tel_what = $tel_what;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->setClient($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getClient() === $this) {
                $panier->setClient(null);
            }
        }

        return $this;
    }
}
