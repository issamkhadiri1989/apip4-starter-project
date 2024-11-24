<?php

declare(strict_types=1);

namespace App\DataFixtures;

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

        $movies = $this->serializer->deserialize($content, type: Media::class.'[]', format: 'json');
        \array_walk($movies, function (Media $media) use($type, $manager) {
            $media->setType($type);
            $manager->persist($media);
        });

        $manager->flush();
    }
}
