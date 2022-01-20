<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 */
class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=OfferType::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=PromoCode::class, mappedBy="offers")
     */
    private $promoCodes;

    public function __construct()
    {
        $this->promoCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?OfferType
    {
        return $this->type;
    }

    public function setType(?OfferType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|PromoCode[]
     */
    public function getPromoCodes(): Collection
    {
        return $this->promoCodes;
    }

    public function addPromoCode(PromoCode $promoCode): self
    {
        if (!$this->promoCodes->contains($promoCode)) {
            $this->promoCodes[] = $promoCode;
            $promoCode->addOffer($this);
        }

        return $this;
    }

    public function removePromoCode(PromoCode $promoCode): self
    {
        if ($this->promoCodes->removeElement($promoCode)) {
            $promoCode->removeOffer($this);
        }

        return $this;
    }

    //returns a list of PromoCode codes
    public function getPromoCodeList(): ?array
    {
        $promocodeList = [];

        foreach($this->promoCodes as $promocode){
            $promoCodeList[] = $promocode->getCode();
        }

        return $promoCodeList;
    }

    public function getTypeName(): ?string
    {
        return $this->type->getName();
    }
}
