<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
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
    private $nomProd;

    /**
     * @ORM\Column(type="integer")
     */
    private $prixProd;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptionProd;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imgProd;

    /**
     * @ORM\Column(type="boolean")
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, mappedBy="produits")
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity=Magasin::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $magasin;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProd(): ?string
    {
        return $this->nomProd;
    }

    public function setNomProd(string $nomProd): self
    {
        $this->nomProd = $nomProd;

        return $this;
    }

    public function getPrixProd(): ?int
    {
        return $this->prixProd;
    }

    public function setPrixProd(int $prixProd): self
    {
        $this->prixProd = $prixProd;

        return $this;
    }

    public function getDescriptionProd(): ?string
    {
        return $this->descriptionProd;
    }

    public function setDescriptionProd(string $descriptionProd): self
    {
        $this->descriptionProd = $descriptionProd;

        return $this;
    }

    public function getImgProd(): ?string
    {
        return $this->imgProd;
    }

    public function setImgProd(?string $imgProd): self
    {
        $this->imgProd = $imgProd;

        return $this;
    }

    public function getPromo(): ?bool
    {
        return $this->promo;
    }

    public function setPromo(bool $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addProduit($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduit($this);
        }

        return $this;
    }

    public function getMagasin(): ?Magasin
    {
        return $this->magasin;
    }

    public function setMagasin(?Magasin $magasin): self
    {
        $this->magasin = $magasin;

        return $this;
    }
}
