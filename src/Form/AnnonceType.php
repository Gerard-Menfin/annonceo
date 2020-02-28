<?php

namespace App\Form;

use App\Entity\Annonce, App\Entity\Membre, App\Entity\Categorie, App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Input;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\CategorieRepository, App\Repository\MembreRepository, App\Repository\PhotoRepository;

class AnnonceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('titre')
            ->add('description_courte')
            ->add('description_longue')
            ->add('prix')
            ->add('adresse')
            ->add('ville')
            ->add('cp')
            ->add('pays')
            
            ->add("photo1", Input\FileType::class, [ "label" => "1ère photo", "mapped" => false,  "required" => false ])
            ->add("photo2", Input\FileType::class, [ "label" => "2ième photo", "mapped" => false, "required" => false ])
            ->add("photo3", Input\FileType::class, [ "label" => "3ième photo", "mapped" => false, "required" => false ])
            ->add("photo4", Input\FileType::class, [ "label" => "4ième photo", "mapped" => false, "required" => false ])
            ->add("photo5", Input\FileType::class, [ "label" => "5ième photo", "mapped" => false, "required" => false ])

            ->add('categorie', EntityType::class, [ 
                "class"         => Categorie::class,
                "choice_label"  => function(Categorie $cat){
                    return $cat->getTitre() . " (" . substr($cat->getMotscles(), 0, 10) . ")";
                },
                "placeholder"   => "Choisissez une catégorie",
            ])
            
            ->add("ajouter", Input\SubmitType::class, [ "label" => "Enregistrer"])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }

}
