<?php

namespace App\Entity;

use App\Repository\GarageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=GarageRepository::class)
 */
class Garage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"garage:index", "user:index", "ad:index", "adminGarage"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"garage:index", "user:index", "adminGarage"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"garage:index", "adminGarage"})
     */
    private $phone;

    /**
     * @ORM\Column(type="text")
     * @Groups({"garage:index", "adminGarage"})
     */
    private $addressRue;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"garage:index", "adminGarage"})
     */
    private $addressCp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"garage:index", "adminGarage"})
     */
    private $addressCity;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="garages")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"garage:index", "adminGarage"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="garage", orphanRemoval=true)
     * @Groups({"garage:index", "adminGarage"})
     */
    private $ads;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
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

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddressRue(): ?string
    {
        return $this->addressRue;
    }

    public function setAddressRue(string $addressRue): self
    {
        $this->addressRue = $addressRue;

        return $this;
    }

    public function getAddressCp(): ?int
    {
        return $this->addressCp;
    }

    public function setAddressCp(int $addressCp): self
    {
        $this->addressCp = $addressCp;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->addressCity;
    }

    public function setAddressCity(string $addressCity): self
    {
        $this->addressCity = $addressCity;

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
     * @return Collection|Ad[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setGarage($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getGarage() === $this) {
                $ad->setGarage(null);
            }
        }

        return $this;
    }
}
