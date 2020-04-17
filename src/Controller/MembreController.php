<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\AnnonceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Repository\MembreRepository as MembreRepo;
use App\Repository\AnnonceRepository as AnnonceRepo;
use App\Repository\CategorieRepository as CategorieRepo;
use App\Entity\Photo, DateTime;

/**
 * @Route("/membre")
 */
class MembreController extends AbstractController
{
    /**
     * @Route("/", name="membre_index")
     */
    public function index (MembreRepo $mr)
    {
        return $this->render('membre/index.html.twig' );
    }

    /**
     * @Route("/les-membres-actifs", name="membres")
     */
    public function membres (MembreRepo $mr, CategorieRepo $cr, AnnonceRepo $ar)
    {
        $categories = $cr->findAll();
        $membres = $mr->findByRole("ROLE_USER");
        $regions = $ar->findRegions();
        return $this->render('membre/index.html.twig', compact("categories", "membres", "regions"));
    }

    /**
     * @Route("/afficher/membre/{pseudo}", name="membre_afficher")
     */
    public function afficher(MembreRepo $mr, int $pseudo)
    {
        $membre = $mr->findBy([ "pseudo" => $pseudo]);
        return $this->render("membre/fiche.html.twig", compact("membre"));
    }


    // /**
    //  * @Route("/profil", name="profil")
    //  * @IsGranted("IS_AUTHENTICATED_FULLY")
    //  */
    // public function profil()
    // {
    //     $mes_annonces = $this->getUser()->getAnnonces();
    //     return $this->render('membre/profil.html.twig', compact("mes_annonces"));
    // }

    /**
     * @Route("/ajouter", name="membre_add")
     */
    public function add(Request $rq, EMI $em)
    {
        # code...
    }

    /**
     * @Route("/liste", name="membre_list")
     */
    public function list(Request $rq, EMI $em)
    {
        # code...
    }

    
}
