<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository as CR;
use App\Repository\AnnonceRepository as AR;
use App\Repository\MembreRepository as MR;


class AccueilController extends AbstractController
{
    public function index(AR $annRepo, CR $catRepo, MR $membreRepo)
    {
        $categories = $catRepo->findAll();
        $membres = $membreRepo->findAll();
        $regions = $annRepo->findRegions();
        $annonces = $annRepo->findAll();
        return $this->render('accueil/index.html.twig', compact("categories", "membres", "annonces", "regions"));
    }
}
