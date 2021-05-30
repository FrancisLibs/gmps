<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OptionRepository::class)
 * @ORM\Table(name="`option`")
 */
class Option
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $displayOrderList;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="options", cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayOrderList(): ?string
    {
        return $this->displayOrderList;
    }

    public function setDisplayOrderList(string $displayOrderList): self
    {
        $this->displayOrderList = $displayOrderList;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        // set the owning side of the relation if necessary
        if ($user->getOptions() !== $this) {
            $user->setOptions($this);
        }

        $this->user = $user;

        return $this;
    }
}
