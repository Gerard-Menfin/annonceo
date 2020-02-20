<?php

namespace App\Form;

use App\Entity\Annonce;
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
            ->add('membre', EntityType::class, [ 
                                                 "class"   => Annonce::class,
                                                 "choices" => $membres
                                               ])
            ->add('photo')
            ->add('categorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }

}
