<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Categorie;

class CategorieFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(10, "cat", function($num){
            $note = (new Categorie)
                        ->setTitre($this->faker->word)
                        ->setMotscles($this->faker->words($nb = 3, $asText = true));
            return $note;
        });

        $manager->flush();
    }
}
