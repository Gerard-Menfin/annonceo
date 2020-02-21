<?php

namespace App\Form;

use App\Entity\Annonce, App\Entity\Membre, App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Input;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\CategorieRepository, App\Repository\MembreRepository, App\Repository\PhotoRepository;

class AnnonceType extends AbstractType
{
    // Pour pouvoir utiliser un Repository dans un TypeForm 
    // (parce que les Form Types sont des services, on peut utiliser les injections de dépendances)
    // On crée une nouvelle propriété...
    private $membreRepo;
    private $categorieRepo;
    private $photoRepo;
    
    public function __construct(MembreRepository $mr, PhotoRepository $pr, CategorieRepository $cr){
        // ... à laquelle on affecte un Repository
        $this->membreRepo = $mr;
        $this->photoRepo = $pr;
        $this->categorieRepo = $cr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $choixMembres = [];
        $membres = $this->membreRepo->createQueryBuilder("m")
                                    ->select("m.email")
                                    ->where("m.roles LIKE :role")
                                    ->setParameter("role", '%"ROLE_USER"%')
                                    ->getQuery()
                                    ->getResult();
        $builder
            ->add('titre')
            ->add('description_courte')
            ->add('description_longue')
            ->add('prix')
            ->add('adresse')
            ->add('ville')
            ->add('cp')
            ->add('pays')
            ->add('photo', Input\CollectionType::class, [
                'entry_type'    => PhotoType::class,
                'entry_options' => ['label' => "Photos"],
            ])
                                                
            ->add('membre', EntityType::class, [ 
                "class"        => Membre::class,
                "choice_label" => "pseudo",
                "placeholder"  => "Choisissez un membre",
            ])

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
