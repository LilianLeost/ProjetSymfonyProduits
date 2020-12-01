<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MagasinRepository::class)
 */
class Magasin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomMag;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $villeMag;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="magasin")
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMag(): ?string
    {
        return $this->nomMag;
    }

    public function setNomMag(string $nomMag): self
    {
        $this->nomMag = $nomMag;

        return $this;
    }

    public function getVilleMag(): ?string
    {
        return $this->villeMag;
    }

    public function setVilleMag(string $villeMag): self
    {
        $this->villeMag = $villeMag;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setMagasin($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getMagasin() === $this) {
                $produit->setMagasin(null);
            }
        }

        return $this;
    }
}
