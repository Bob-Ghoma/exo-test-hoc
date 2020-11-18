<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 * @ApiResource(
 *   collectionOperations={
 *     "get"={
 *       "normalization_context"={"groups"={"invoice_list"}}
 *     },
 *     "post"
 *   },
 *   itemOperations={
 *     "get"={
 *       "normalization_context"={"groups"={"invoice_list", "invoice_details"}}
 *     },
 *    "put",
 *    "patch",
 *    "delete",
 *   }
 * )
 * @UniqueEntity("number")
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"invoice_list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice_list"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     * @Assert\Length(min="5")
     * @Groups({"invoice_list"})
     */
    private $number;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"invoice_details"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoice_details"})
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="invoice", orphanRemoval=true)
     * @Groups({"invoice_details"})
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setInvoice($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getInvoice() === $this) {
                $product->setInvoice(null);
            }
        }

        return $this;
    }

  /**
   * @Groups({"invoice_details"})
   */
    public function getTotalHt(){
      return array_reduce($this->products->toArray(), function($total, $product) {return $total+$product->getPriceHt();});
    }

  /**
   * @Groups({"invoice_list"})
   */
    public function getTotal(){
      return array_reduce($this->products->toArray(), function($total, $product) {return $total+$product->getPrice();});
    }

  /**
   * @Groups({"invoice_details"})
   */
    public function getTotalTva(){
      return array_reduce($this->products->toArray(), function($total, $product) {return $total+$product->getTva();});
    }
}
