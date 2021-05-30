<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $itemDesignation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $itemReference;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $itemQuantitie;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="items")
     */
    private $order;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemDesignation(): ?string
    {
        return $this->itemDesignation;
    }

    public function setItemDesignation(string $itemDesignation): self
    {
        $this->itemDesignation = $itemDesignation;

        return $this;
    }

    public function getItemReference(): ?string
    {
        return $this->itemReference;
    }

    public function setItemReference(?string $itemReference): self
    {
        $this->itemReference = $itemReference;

        return $this;
    }

    public function getItemQuantitie(): ?int
    {
        return $this->itemQuantitie;
    }

    public function setItemQuantitie(int $itemQuantitie): self
    {
        $this->itemQuantitie = $itemQuantitie;

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
}
