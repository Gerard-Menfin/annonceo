<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Input;
use Symfony\Component\Validator\Constraints as Contrainte;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', Input\TextType::class, [ "help" => "Tapez le titre de la catégorie",
                                                    "attr" => [ "placeholder" => "Catégorie"],
                                                    "constraints" => [ 
                                                                        new Contrainte\NotBlank(["message" => "Vous avez oublié de remplir ce champ"]),
                                                                        new Contrainte\Length([ "min" => 2, "max" => 20, 
                                                                                                "minMessage" => "Le titre doit avoir au moins 2 caractères",
                                                                                                "maxMessage" => "Le titre ne doit pas dépasser 20 caractères"
                                                                                                ])
                                                                        ]
                                            ])
            ->add('motscles', Input\TextareaType::class, [ "label" => "Mots clés", 
                                                     "help" => "Séparez les mots clés par des ','" ])
            ->add("ajouter", Input\SubmitType::class, [ "label" => "Enregistrer"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
