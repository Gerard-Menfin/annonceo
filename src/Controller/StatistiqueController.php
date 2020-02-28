<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NoteRepository;
use App\Repository\AnnonceRepository;

class StatistiqueController extends AbstractController
{
    /**
     * @Route("/statistique", name="statistique")
     */
    public function index(NoteRepository $nr, AnnonceRepository $ar)
    {
        $top5membres = $nr->findTop5MembresNotes();
        $top5categories = $ar->findTop5Categories();
        dd($top5categories);

        return $this->render('statistique/index.html.twig');
    }
}
