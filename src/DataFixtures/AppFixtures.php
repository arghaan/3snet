<?php

namespace App\DataFixtures;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;

class AppFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create("ru_RU");

        for ($i = 0; $i < 100; $i++) {
            $post = new Post();
            $post->setText($faker->realText())
                ->setCreatedAt(new DateTime($faker->dateTime()));
            $manager->persist($post);
        }
        $manager->flush();
    }
}
