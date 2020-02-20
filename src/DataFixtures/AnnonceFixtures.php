<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Annonce;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnnonceFixtures extends BaseFixture implements DependentFixtureInterface
{

    public function loadData(ObjectManager $manager)
    {

        $this->createMany(20, "annonce", function($num){

            $ann = (new Annonce)
                        ->setTitre($this->faker->sentence($nbWords = 6, $variableNbWords = true))
                        ->setDescriptionCourte($this->faker->paragraph($nbSentences = 1, $variableNbSentences = true))
                        ->setDescriptionLongue($this->faker->paragraph($nbSentences = 5, $variableNbSentences = true))
                        ->setPrix($this->faker->randomNumber(5))
                        ->setAdresse($this->faker->streetAddress)
                        ->setVille($this->faker->city)
                        ->setCp($this->faker->randomNumber($nbDigits = 5, $strict = true))
                        ->setPays("France")
                        ->setMembre($this->getRandomReference("membre"))
                        ->setCategorie($this->getRandomReference("cat"))
                        ->setPhoto($this->getRandomReference("photo"))
                        ->setDateEnregistrement($this->faker->dateTime());
            return $ann;
        });

        $manager->flush();
    }

    public function getDependencies(){
        return [ MembreFixtures::class, CategorieFixtures::class, PhotoFixtures::class ];
    }    
}
