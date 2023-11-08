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

    #[ORM\ManyToMany(targetEntity: Color::class, inversedBy: 'manaValue')]
    private Collection $colors;

    #[ORM\Column]
    private ?int $manaValue = null;

    #[ORM\Column(nullable: true)]
    private ?float $power = null;

    #[ORM\Column(nullable: true)]
    private ?float $toughness = null;

    #[ORM\Column]
    private ?int $loyalty = null;

    #[ORM\Column(nullable: true)]
    private ?int $defense = null;

    #[ORM\Column(length: 255)]
    private ?string $typeLine = null;

    #[ORM\ManyToMany(targetEntity: Keyword::class, inversedBy: 'cards')]
    private Collection $keywords;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $effectText = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $flavorText = null;

    #[ORM\ManyToMany(targetEntity: Color::class, inversedBy: 'cards')]
    private Collection $colorIdentity;

    #[ORM\Column(length: 255)]
    private ?string $rarity = null;

    #[ORM\ManyToOne(inversedBy: 'image')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Set $set = null;

    #[ORM\OneToOne(inversedBy: 'card', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Media $image = null;

    #[ORM\OneToOne(inversedBy: 'card', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Media $art = null;

    #[ORM\Column(length: 255)]
    private ?string $artist = null;

    public function __construct()
    {
        $this->colors = new ArrayCollection();
        $this->keywords = new ArrayCollection();
        $this->colorIdentity = new ArrayCollection();
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

    /**
     * @return Collection<int, Color>
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): static
    {
        if (!$this->colors->contains($color)) {
            $this->colors->add($color);
        }

        return $this;
    }

    public function removeColor(Color $color): static
    {
        $this->colors->removeElement($color);

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

    public function setLoyalty(int $loyalty): static
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

    public function getTypeLine(): ?string
    {
        return $this->typeLine;
    }

    public function setTypeLine(string $typeLine): static
    {
        $this->typeLine = $typeLine;

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

    /**
     * @return Collection<int, Color>
     */
    public function getColorIdentity(): Collection
    {
        return $this->colorIdentity;
    }

    public function addColorIdentity(Color $colorIdentity): static
    {
        if (!$this->colorIdentity->contains($colorIdentity)) {
            $this->colorIdentity->add($colorIdentity);
        }

        return $this;
    }

    public function removeColorIdentity(Color $colorIdentity): static
    {
        $this->colorIdentity->removeElement($colorIdentity);

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

    public function getSet(): ?Set
    {
        return $this->set;
    }

    public function setSet(?Set $set): static
    {
        $this->set = $set;

        return $this;
    }

    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function setImage(Media $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getArt(): ?Media
    {
        return $this->art;
    }

    public function setArt(Media $art): static
    {
        $this->art = $art;

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
