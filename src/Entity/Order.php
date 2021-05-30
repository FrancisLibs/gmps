<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="orders")
 * @UniqueEntity("orderNumber")
 */
class Order
{
    const EN_COURS = 0;
    const EN_ATTENTE = 1;
    const CLOTUREE = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $orderNumber;

    /**
     * 
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=DeliveryForm::class, mappedBy="order", orphanRemoval=true)
     */
    private $deliveryForms;

    /**
     * @ORM\ManyToOne(targetEntity=Provider::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provider;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="order")
     */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity=Invoice::class, inversedBy="orders")
     */
    private $invoice;

    /**
     * @ORM\Column(type="float")
     */
    private $expectedAmount;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $designation;

    /**
     * @ORM\ManyToMany(targetEntity=Account::class, inversedBy="orders")
     */
    private $account;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $expectedDeliveryDate;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank
     */
    private $status;

    public function __construct()
    {
        $this->deliveryForms = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->invoice = new ArrayCollection();
        $this->account = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
            $deliveryForm->setOrder($this);
        }

        return $this;
    }

    public function removeDeliveryForm(DeliveryForm $deliveryForm): self
    {
        if ($this->deliveryForms->removeElement($deliveryForm)) {
            // set the owning side to null (unless already changed)
            if ($deliveryForm->getOrder() === $this) {
                $deliveryForm->setOrder(null);
            }
        }

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrder($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrder() === $this) {
                $item->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoice(): Collection
    {
        return $this->invoice;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoice->contains($invoice)) {
            $this->invoice[] = $invoice;
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        $this->invoice->removeElement($invoice);

        return $this;
    }

    public function getExpectedAmount(): ?float
    {
        return $this->expectedAmount;
    }

    public function setExpectedAmount(float $expectedAmount): self
    {
        $this->expectedAmount = $expectedAmount;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccount(): Collection
    {
        return $this->account;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->account->contains($account)) {
            $this->account[] = $account;
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        $this->account->removeElement($account);

        return $this;
    }

    public function getExpectedDeliveryDate(): ?\DateTimeInterface
    {
        return $this->expectedDeliveryDate;
    }

    public function setExpectedDeliveryDate(?\DateTimeInterface $expectedDeliveryDate): self
    {
        $this->expectedDeliveryDate = $expectedDeliveryDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTextStatus(): ?string
    {
        switch($this->status) {
            case 0:
                return "En cours";
                break;
            case 1:
                return "En attente";
                break;
            case 2:
                return "Cloturée";
                break;
        }
        
    }
}
