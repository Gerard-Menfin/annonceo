<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MembreRepository as MR;
use App\Form\AttribuerNoteType;

class NoteController extends AbstractController
{
    /**
     * @Route("/note", name="note")
     */
    public function index()
    {
        return $this->render('note/index.html.twig', [
            'controller_name' => 'NoteController',
        ]);
    }

    /**
     * @Route("/profil/attribuer-note/{pseudo}", name="attribuer_note")
     */
    public function attribuer(MR $mr, $pseudo){
        $membre = $mr->findOneBy([ "pseudo" => $pseudo ]);
        $form = $this->createForm(AttribuerNoteType::class);
        $form = $form->createView();
        return $this->render("note/attribuer.html.twig", compact("membre", "form"));
    }
}
