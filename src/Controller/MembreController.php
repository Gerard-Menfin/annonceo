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

class MembreController extends AbstractController
{
    /**
     * @Route("/membre", name="membre")
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


    /**
     * @Route("/profil", name="profil")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function profil()
    {
        $mes_annonces = $this->getUser()->getAnnonces();
        return $this->render('membre/profil.html.twig', compact("mes_annonces"));
    }

    /**
     * @Route("/membre/ajouter", name="membre_add")
     */
    public function add(Request $rq, EMI $em)
    {
        # code...
    }

    /**
     * @Route("/membre/liste", name="membre_list")
     */
    public function list(Request $rq, EMI $em)
    {
        # code...
    }

    
    /**
     * @Route("/profil/annonces/ajouter", name="nouvelle_annonce")
     */
    public function nouvelle_annonce(Request $rq, EMI $em){
        $form = $this->createForm(AnnonceType::class);
        $form->handleRequest($rq);

        if($form->isSubmitted()){
            if($form->isValid()){
                $nvlAnnonce = $form->getData();
                $album = new Photo;

                $destination = $this->getParameter("dossier_images_annonces");
                for($i=1; $i<=5; $i++){
                    $champ = "photo" . $i;
                    if($photoUploadee = $form[$champ]->getData()){
                        $nomPhoto = pathinfo($photoUploadee->getClientOriginalName(), PATHINFO_FILENAME);
                        $nouveauNom = trim($nomPhoto);
                        $nouveauNom = str_replace(" ", "_", $nouveauNom);
                        $nouveauNom .= "-" . uniqid() . "." . $photoUploadee->guessExtension();
                        $photoUploadee->move($destination, $nouveauNom);
                        $setter = "setPhoto$i";
                        $album->$setter($nouveauNom);
                    
                    }
                }
                $em->persist($album);
                $em->flush();
                $nvlAnnonce->setDateEnregistrement(new DateTime());
                $nvlAnnonce->setPhoto($album);
                $nvlAnnonce->setMembre($this->getUser());
                $em->persist($nvlAnnonce);
                $em->flush();
                $this->addFlash("success", "Votre annonce a bien été enregistrée");
                return $this->redirectToRoute("profil");
            }
            else{
                $this->addFlash("error", "Il manque des informations pour enregistrer votre annonce");

            }
        }

        $form = $form->createView();
        return $this->render("membre/annonce.html.twig", compact("form"));
    }
    
    /**
     * @Route("/profil/annonces/modifier/{id}", name="modifier_annonce")
     * 
     */
    public function modifier_annonce(Request $rq, AnnonceRepo $ar, EMI $em, int $id)
    {
        $annonceAmodifier = $ar->find($id);
        if($annonceAmodifier && $annonceAmodifier->getMembre()->getId() == $this->getUser()->getId()){
            $form = $this->createForm(AnnonceType::class, $annonceAmodifier);
            $form->handleRequest($rq);
            if($form->isSubmitted()){
                if($form->isValid()){
                    $destination = $this->getParameter("dossier_images_annonces");
                    for($i=1; $i<=5; $i++){
                        $champ = "photo" . $i;
                        if($photoUploadee = $form[$champ]->getData()){
                            $nomPhoto = pathinfo($photoUploadee->getClientOriginalName(), PATHINFO_FILENAME);
                            $nouveauNom = trim($nomPhoto);
                            $nouveauNom = str_replace(" ", "_", $nouveauNom);
                            $nouveauNom .= "-" . uniqid() . "." . $photoUploadee->guessExtension();
                            $photoUploadee->move($destination, $nouveauNom);
                            $setter = "setPhoto$i";
                            $annonceAmodifier->getPhoto()->$setter($nouveauNom);
                        }
                    }
                    $em->persist($annonceAmodifier);
                    $em->flush();
                    $this->addFlash("success", "Votre annonce a bien été modifiée");
                    return $this->redirectToRoute("profil");
                }
                else{
                    $this->addFlash("error", "Il manque des informations pour enregistrer vos modifications");
                }
            }
            $form = $form->createView();
            return $this->render("membre/annonce.html.twig", compact("form"));
        }
        else {
            $this->addFlash("error", "Vous ne pouvez pas accéder à cet URL");
            return $this->redirectToRoute("profil");
        }
    }
}
