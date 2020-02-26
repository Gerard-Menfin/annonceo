<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AnnonceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Entity\Annonce;
use App\Repository\AnnonceRepository as Repo;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="annonce")
     */
    public function index()
    {

        return $this->render('annonce/list.html.twig', []);
    }

    /**
     * @Route("/annonce/liste", name="annonce_list")
     */
    public function list(Request $rq, EMI $em, Repo $repo )
    {
        $annonces = $repo->findAll();
        return $this->render('annonce/list.html.twig', compact("annonces"));
    }



    /**
     * @Route("/annonce/ajouter", name="annonce_add")
     */
    public function form(Request $rq, EMI $em){
        $form = $this->createForm(AnnonceType::class);
        $form->handleRequest($rq);

        if( $form->isSubmitted()){
            if($form->isValid() ){
                dd($form);
                $data = $form->getData();
                $data->setDateEnregistrement( date_create("now") );
                dd($data);
                
                $file = $form['attachment']->getData();
                $file->move($directory, $someNewFilename);
        

                $em->persist($data);
                $em->flush();

                $this->addFlash('success', 'La catégorie  a bien été enregistrée !');
                return $this->redirectToRoute("accueil");
            }
            else{

            }
        }
        $form = $form->createView();
        return $this->render("annonce/form.html.twig", compact("form"));
    }
}
