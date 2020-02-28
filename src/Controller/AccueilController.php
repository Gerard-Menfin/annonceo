<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository as CR;
use App\Repository\AnnonceRepository as AR;
use App\Repository\MembreRepository as MR;
use Symfony\Component\HttpFoundation\Request;


class AccueilController extends AbstractController
{

    public function index(Request $rq, AR $annRepo, CR $catRepo, MR $membreRepo)
    {
        $categorie_choisie = null;
        $membre_choisi = null;
        $ville_choisie = null;
        $prix_choisi = 0;

        if($rq->getMethod() == "POST"){
            $where = [];
            if($categorie_choisie = $rq->request->get("categorie")){

                $where["categorie"] = $categorie_choisie;
            }
            
            if($membre_choisi = $rq->request->get("membre")){
                $where["membre"] = $membre_choisi;
            }
            
            if($ville_choisie = $rq->request->get("region")){
                $where["ville"] = $ville_choisie;
            }

            $annonces = $annRepo->findBy($where);
            
            if($prix_choisi = $rq->request->get("prix")){
                
                // La fonction array_filter permet de filtre les valeurs d'un array selon le résultat d'une fonction
                // (appelé callback). 
                // Cette fonction doit retourner un boolean (si le retour vaut true, la valeur
                // de l'array est gardée dans le résultat final). 
                // Array_filter retourne un Array
                // NB : la fonction callback n'a pas accès aux variables exterieures à sa déclaration. Pour 
                //      pouvoir utiliser une variable existante, il faut utiliser 'use'
                $annonces = array_filter($annonces, function($ann) use($prix_choisi){
                    return $ann->getPrix() <= $prix_choisi;
                });
            }
        }
        else {
            $mot = $rq->query->get("recherche");
            if($mot){
                $cats = $catRepo->recherche($mot);
                $annonces = [];
                foreach($cats as $cat){
                   foreach ($cat->getAnnonces() as $annonceCategorie ) {
                       $annonces[] = $annonceCategorie;
                   }
                }      
            } else{
                $annonces = $annRepo->findAll();
            }
        }
        
        $categories = $catRepo->findAll();
        $membres = $membreRepo->findByRole("ROLE_USER");
        $regions = $annRepo->findRegions();
        return $this->render('accueil/index.html.twig', compact("categories", "membres", "annonces", "regions", "prix_choisi", "ville_choisie", "membre_choisi", "categorie_choisie"));
    }
}
