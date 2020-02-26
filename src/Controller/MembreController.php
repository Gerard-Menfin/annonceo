<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\AnnonceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Entity\Photo, DateTime;

class MembreController extends AbstractController
{
    /**
     * @Route("/membre", name="membre")
     */
    public function index()
    {
        return $this->render('membre/index.html.twig', [
            'controller_name' => 'MembreController',
        ]);
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
    
}
