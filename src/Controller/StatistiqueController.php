<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NoteRepository;

class StatistiqueController extends AbstractController
{
    /**
     * @Route("/statistique", name="statistique")
     */
    public function index(NoteRepository $nr)
    {
        $top5membres = $nr->findTop5MembresNotes();
        dd($top5membres);

        return $this->render('statistique/index.html.twig');
    }
}
