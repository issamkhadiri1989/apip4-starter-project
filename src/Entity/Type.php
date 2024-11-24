<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'type')]
    private Collection $catalog;

    public function __construct()
    {
        $this->catalog = new ArrayCollection();
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
     * @return Collection<int, Media>
     */
    public function getCatalog(): Collection
    {
        return $this->catalog;
    }

    public function addCatalog(Media $catalog): static
    {
        if (!$this->catalog->contains($catalog)) {
            $this->catalog->add($catalog);
            $catalog->setType($this);
        }

        return $this;
    }

    public function removeCatalog(Media $catalog): static
    {
        if ($this->catalog->removeElement($catalog)) {
            // set the owning side to null (unless already changed)
            if ($catalog->getType() === $this) {
                $catalog->setType(null);
            }
        }

        return $this;
    }
}
