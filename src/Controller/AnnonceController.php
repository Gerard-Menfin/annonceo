<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AnnonceType;

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
     * @Route("/annonce/ajouter", name="annonce_add")
     */
    public function form(){
        $form = $this->createForm(AnnonceType::class);
        $form = $form->createView();
        return $this->render("annonce/form.html.twig", compact("form"));
    }
}
