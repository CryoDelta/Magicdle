<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $cost = null;

    #[ORM\Column(length: 255)]
    private ?string $colors = null;

    #[ORM\Column]
    private ?int $manaValue = null;

    #[ORM\Column(nullable: true)]
    private ?float $power = null;

    #[ORM\Column(nullable: true)]
    private ?float $toughness = null;

    #[ORM\Column(nullable: true)]
    private ?int $loyalty = null;

    #[ORM\Column(nullable: true)]
    private ?int $defense = null;

    #[ORM\Column(length: 255)]
    private ?string $typeline = null;

    #[ORM\ManyToMany(targetEntity: Keyword::class, inversedBy: 'cards')]
    private Collection $keywords;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $effectText = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $flavorText = null;

    #[ORM\Column(length: 255)]
    private ?string $colorIdentity = null;

    #[ORM\Column(length: 255)]
    private ?string $rarity = null;

    #[ORM\ManyToOne(inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Set $lastSet = null;

    #[ORM\OneToMany(mappedBy: 'card', targetEntity: Media::class, orphanRemoval: true)]
    private Collection $media;

    #[ORM\Column(length: 255)]
    private ?string $artist = null;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->media = new ArrayCollection();
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

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getColors(): ?string
    {
        return $this->colors;
    }

    public function setColors(string $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    public function getManaValue(): ?int
    {
        return $this->manaValue;
    }

    public function setManaValue(int $manaValue): static
    {
        $this->manaValue = $manaValue;

        return $this;
    }

    public function getPower(): ?float
    {
        return $this->power;
    }

    public function setPower(?float $power): static
    {
        $this->power = $power;

        return $this;
    }

    public function getToughness(): ?float
    {
        return $this->toughness;
    }

    public function setToughness(?float $toughness): static
    {
        $this->toughness = $toughness;

        return $this;
    }

    public function getLoyalty(): ?int
    {
        return $this->loyalty;
    }

    public function setLoyalty(?int $loyalty): static
    {
        $this->loyalty = $loyalty;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(?int $defense): static
    {
        $this->defense = $defense;

        return $this;
    }

    public function getTypeline(): ?string
    {
        return $this->typeline;
    }

    public function setTypeline(string $typeline): static
    {
        $this->typeline = $typeline;

        return $this;
    }

    /**
     * @return Collection<int, Keyword>
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): static
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords->add($keyword);
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): static
    {
        $this->keywords->removeElement($keyword);

        return $this;
    }

    public function getEffectText(): ?string
    {
        return $this->effectText;
    }

    public function setEffectText(?string $effectText): static
    {
        $this->effectText = $effectText;

        return $this;
    }

    public function getFlavorText(): ?string
    {
        return $this->flavorText;
    }

    public function setFlavorText(?string $flavorText): static
    {
        $this->flavorText = $flavorText;

        return $this;
    }

    public function getColorIdentity(): ?string
    {
        return $this->colorIdentity;
    }

    public function setColorIdentity(string $colorIdentity): static
    {
        $this->colorIdentity = $colorIdentity;

        return $this;
    }

    public function getRarity(): ?string
    {
        return $this->rarity;
    }

    public function setRarity(string $rarity): static
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getLastSet(): ?Set
    {
        return $this->lastSet;
    }

    public function setLastSet(?Set $lastSet): static
    {
        $this->lastSet = $lastSet;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setCard($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getCard() === $this) {
                $medium->setCard(null);
            }
        }

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): static
    {
        $this->artist = $artist;

        return $this;
    }
}
