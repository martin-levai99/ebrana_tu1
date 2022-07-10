<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Post;
use DateTime;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $post = new Post();
        $post->setTitle("Článek 1");
        $post->setExcerpt("Popisek zde.");
        $post->setContent("Content");
        $post->setPublishDate(new DateTime("now"));
        $post->setThumbnail("thumbanil/url");
        $post->addCategory($this->getReference("category_1"));
        $post->addCategory($this->getReference("category_2"));
        $manager->persist($post);

        $post2 = new Post();
        $post2->setTitle("Článek 2");
        $post2->setExcerpt("Popisek zde. Popisek zde. Popisek zde. Popisek zde.");
        $post2->setContent("Content2");
        $post2->setPublishDate(new DateTime("now"));
        $post2->setThumbnail("thumbanil/url");
        $post->addCategory($this->getReference("category_2"));
        $post->addCategory($this->getReference("category_3"));
        $manager->persist($post2);

        $manager->flush();

    }
}
