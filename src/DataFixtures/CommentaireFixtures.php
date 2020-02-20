<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Commentaire;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentaireFixtures extends BaseFixture implements DependentFixtureInterface
{

    public function loadData(ObjectManager $manager)
    {

        $this->createMany(2, "commentaire", function($num){

            $comm = (new Commentaire)
                        ->setCommentaire($this->faker->paragraph($nbSentences = 1, $variableNbSentences = true))
                        ->setDateEnregistrement($this->faker->dateTime())
                        ->setMembre($this->getRandomReference("membre"))
                        ->setAnnonce($this->getRandomReference("annonce"));
            return $comm;
        });

        $manager->flush();
    }

    public function getDependencies(){
        return [ MembreFixtures::class, AnnonceFixtures::class ];
    }    
}