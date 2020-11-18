<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *   collectionOperations={}
 * )
 */
class Product
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @Groups({"invoice_details"})
   */
  private $id;

  /**
   * @ORM\Column(type="text")
   * @Assert\NotNull()
   * @Assert\Length(min="10")
   */
  private $title;

  /**
   * @ORM\Column(type="float")
   * @Assert\NotNull()
   * @Assert\PositiveOrZero()
   * @Groups({"invoice_details"})
   */
  private $puht;

  /**
   * @ORM\Column(type="integer")
   * @Assert\NotNull()
   * @Assert\Type(type="integer")
   * @Assert\Positive()
   * @Groups({"invoice_details"})
   */
  private $quantity;

  /**
   * @ORM\Column(type="boolean")
   * @Assert\NotNull()
   * @Groups({"invoice_details"})
   */
  private $reducedTva;

  /**
   * @ORM\ManyToOne(targetEntity=Invoice::class, inversedBy="products")
   * @ORM\JoinColumn(nullable=false)
   * @Assert\NotNull()
   */
  private $invoice;

  /**
   * Product constructor.
   */
  public function __construct()
  {
    $this->reducedTva = true;
  }


  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): self
  {
    $this->title = $title;

    return $this;
  }

  public function getPuht(): ?float
  {
    return $this->puht;
  }

  public function setPuht(float $puht): self
  {
    $this->puht = $puht;

    return $this;
  }

  public function getQuantity(): ?int
  {
    return $this->quantity;
  }

  public function setQuantity(int $quantity): self
  {
    $this->quantity = $quantity;

    return $this;
  }

  public function getReducedTva(): ?bool
  {
    return $this->reducedTva;
  }

  public function setReducedTva(bool $reducedTva): self
  {
    $this->reducedTva = $reducedTva;

    return $this;
  }

  public function getInvoice(): ?Invoice
  {
    return $this->invoice;
  }

  public function setInvoice(?Invoice $invoice): self
  {
    $this->invoice = $invoice;

    return $this;
  }


  /**
   * @Groups({"invoice_details"})
   */
  public function getTva()
  {
    dump($this->getPriceHt() * ($this->reducedTva ? 0.02 : 0.01));
    return $this->getPriceHt() * ($this->reducedTva ? 0.02 : 0.01);
  }


  /**
   * @Groups({"invoice_details"})
   */
  public function getPrice()
  {
    return $this->getPriceHt() + $this->getTva();
  }

  /**
   * @Groups({"invoice_details"})
   */
  public function getPriceHt()
  {
    return $this->puht * $this->quantity;
  }

}
