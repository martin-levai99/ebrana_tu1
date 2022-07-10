<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cat = new Category();
        $cat->setTitle("Category 1");
        $manager->persist($cat);

        $cat2 = new Category();
        $cat2->setTitle("Category 2");
        $manager->persist($cat2);

        $cat3 = new Category();
        $cat3->setTitle("Category 3");
        $manager->persist($cat3);

        $manager->flush();



        $this->addReference("category_1", $cat);
        $this->addReference("category_2", $cat2);
        $this->addReference("category_3", $cat3);

    }
}
