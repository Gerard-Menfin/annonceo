<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MembreRepository as MR;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Symfony\Component\HttpFoundation\Request;
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
    public function attribuer(MR $mr, Request $rq, EMI $em, $pseudo){
        if($pseudo == $this->getUser()->getPseudo()){
            $this->addFlash("error", "Vous ne pouvez pas vous noter vous-même, petit salopiaud !");
            return $this->redirectToRoute("profil");
        }

        $membre = $mr->findOneBy([ "pseudo" => $pseudo ]);

        $form = $this->createForm(AttribuerNoteType::class);
        $form->handleRequest($rq);
        if($form->isSubmitted()){
            if($form->isValid()){
                $note = $form->getData();
                $note->setMembreNotant($this->getUser());
                $note->setDateEnregistrement(new \DateTime());
                $note->setMembreNote($membre);
                $em->persist($note);
                $em->flush();
                $this->addFlash("success", "Votre note a bien été prise en compte");
                return $this->redirectToRoute("profil");
            }
            else{
                $this->addFlash("error", "Une erreur est survenu !");
            }
        }

        $form = $form->createView();
        return $this->render("note/attribuer.html.twig", compact("membre", "form"));
    }
}
