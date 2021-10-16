<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create("ru_RU");

        for ($i = 0; $i < 100; $i++) {
            $post = new Post();
            $post->setText($faker->realText(200))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($post);
        }
        $manager->flush();
    }
}
