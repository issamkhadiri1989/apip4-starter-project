<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\QueryParameter;
use App\Repository\MediaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ApiResource(
//    paginationEnabled: false,
    operations: [
        new Get(
            normalizationContext: [
                'groups' => ['api:read']
            ]
        ),
        new GetCollection(
            paginationEnabled: true,
            paginationItemsPerPage: 5,
            paginationClientItemsPerPage:true,
            paginationMaximumItemsPerPage: 5,
//            order: ['year' => 'DESC'],
            parameters: [
                'order[:property]' => new QueryParameter(filter: 'movies.order_filter'),
            ],
            filters: ['movies.search_filter', 'movies.date_filter', ' movies.range_filter'],
        ),
    ]
)]
#[ApiResource(
    operations: [
        new GetCollection()
    ],
    uriTemplate: '/categories/{id}/catalog',
    uriVariables: [
        'id' => new Link(fromClass: Category::class, toProperty: 'category')
    ],
)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[SerializedName("Title")]
    #[Groups(['api:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[SerializedName("Released")]
    #[Groups(['api:read'])]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column]
    #[SerializedName("Year")]
    private  $year = null;

    #[ORM\Column(type: Types::TEXT)]
    #[SerializedName("Plot")]
    #[Groups(['api:read'])]
    private ?string $plot = null;

    #[ORM\ManyToOne(inversedBy: 'catalog')]
    private ?Type $type = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[Ignore]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setYear( $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(string $plot): static
    {
        $this->plot = $plot;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
