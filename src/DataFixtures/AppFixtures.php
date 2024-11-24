<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $type = new Type();
        $type->setName('Movie');
        $manager->persist($type);

        $content = \file_get_contents(__DIR__ . '/movies-250.json');

        $categories = \array_unique(\array_column(\json_decode($content), "Genre"));

        $categoriesCollection = [];

        foreach ($categories as $category) {
            $labels = \preg_split("/,\s*/", $category);
            $categoriesCollection = \array_merge($categoriesCollection, $labels);
        }

        $categories = \array_unique($categoriesCollection);

        /** @var Category[] $items */
        $items = [];

        foreach ($categories as $category) {
            $item = new Category();
            $item->setName($category);
            $manager->persist($item);

            $items[] = $item;
        }

        $movies = $this->serializer->deserialize($content, type: Media::class.'[]', format: 'json');
        \array_walk($movies, function (Media $media) use($type, $manager, $items) {
            $media->setType($type);
            $media->setCategory($items[\array_rand($items)]);

            $manager->persist($media);
        });

        $manager->flush();
    }
}
