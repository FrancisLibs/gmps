<?php

namespace App\Entity;

use App\Repository\DeliveryFormRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DeliveryFormRepository::class)
 */
class DeliveryForm
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
    private $deliveryFormNumber;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deliveryFormDate;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="deliveryForms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity=Invoice::class, inversedBy="deliveryForms")
     */
    private $invoice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveryFormNumber(): ?string
    {
        return $this->deliveryFormNumber;
    }

    public function setDeliveryFormNumber(string $deliveryFormNumber): self
    {
        $this->deliveryFormNumber = $deliveryFormNumber;

        return $this;
    }

    public function getDeliveryFormDate(): ?\DateTimeInterface
    {
        return $this->deliveryFormDate;
    }

    public function setDeliveryFormDate(\DateTimeInterface $deliveryFormDate): self
    {
        $this->deliveryFormDate = $deliveryFormDate;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

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
}
