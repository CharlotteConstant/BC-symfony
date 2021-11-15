<?php

namespace App\Entity;

use App\Repository\AdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 */
class Ad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ad:index", "adminAds"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ad:index", "adminAds"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"ad:index", "adminAds"})
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Groups({"ad:index", "adminAds"})
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"ad:index", "adminAds"})
     */
    private $kilometers;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"ad:index", "adminAds"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Fuel::class, inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ad:index", "adminAds"})
     */
    private $fuel;

    /**
     * @ORM\ManyToOne(targetEntity=Model::class, inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ad:index", "adminAds"})
     */
    private $model;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="ad", orphanRemoval=true)
     * @Groups({"ad:index", "adminAds"})
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity=Garage::class, inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ad:index", "adminAds"})
     */
    private $garage;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ad:index", "adminAds"})
     */
    private $user;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getKilometers(): ?int
    {
        return $this->kilometers;
    }

    public function setKilometers(int $kilometers): self
    {
        $this->kilometers = $kilometers;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFuel(): ?Fuel
    {
        return $this->fuel;
    }

    public function setFuel(?Fuel $fuel): self
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAd($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAd() === $this) {
                $photo->setAd(null);
            }
        }

        return $this;
    }

    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): self
    {
        $this->garage = $garage;

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
}
