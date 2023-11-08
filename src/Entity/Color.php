<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Card::class, mappedBy: 'colors')]
    private Collection $cardColors;

    #[ORM\ManyToMany(targetEntity: Card::class, mappedBy: 'colorIdentity')]
    private Collection $cardIdentities;

    public function __construct()
    {
        $this->cardColors = new ArrayCollection();
        $this->cardIdentities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getCardsByColors(): Collection
    {
        return $this->cardColors;
    }

    public function addCardColor(Card $card): static
    {
        if (!$this->cardColors->contains($card)) {
            $this->cardColors->add($card);
            $card->addColor($this);
        }

        return $this;
    }

    public function removeCardColor(Card $card): static
    {
        if ($this->manaValue->removeElement($card)) {
            $card->removeColor($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getCardsByIdentity(): Collection
    {
        return $this->cardIdentities;
    }

    public function addCardIdentity(Card $card): static
    {
        if (!$this->cardIdentities->contains($card)) {
            $this->cardIdentities->add($card);
            $card->addColorIdentity($this);
        }

        return $this;
    }

    public function removeCardIdentity(Card $card): static
    {
        if ($this->cardIdentities->removeElement($card)) {
            $card->removeColorIdentity($this);
        }

        return $this;
    }
}
