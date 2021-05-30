<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     */
    private $invoiceNumber;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $invoiceDate;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $amount;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, mappedBy="invoice")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=DeliveryForm::class, mappedBy="invoice")
     */
    private $deliveryForms;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->deliveryForms = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoiceDate;
    }

    public function setInvoiceDate(\DateTimeInterface $invoiceDate): self
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addInvoice($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeInvoice($this);
        }

        return $this;
    }

    /**
     * @return Collection|DeliveryForm[]
     */
    public function getDeliveryForms(): Collection
    {
        return $this->deliveryForms;
    }

    public function addDeliveryForm(DeliveryForm $deliveryForm): self
    {
        if (!$this->deliveryForms->contains($deliveryForm)) {
            $this->deliveryForms[] = $deliveryForm;
            $deliveryForm->setInvoice($this);
        }

        return $this;
    }

    public function removeDeliveryForm(DeliveryForm $deliveryForm): self
    {
        if ($this->deliveryForms->removeElement($deliveryForm)) {
            // set the owning side to null (unless already changed)
            if ($deliveryForm->getInvoice() === $this) {
                $deliveryForm->setInvoice(null);
            }
        }

        return $this;
    }  
}
