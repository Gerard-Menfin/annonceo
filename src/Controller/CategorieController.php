<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Form\CategorieType;
use App\Entity\Categorie;
use App\Repository\CategorieRepository as CR;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(CR $cr)
    {
        $categories = $cr->findAll();
        return $this->render('categorie/list.html.twig', compact("categories"));
    }

    /**
     * @Route("/categorie/add", name="categorie_form", methods="GET")
     */
    public function form()
    {
        $form = $this->createForm(CategorieType::class);
        return $this->render('categorie/form.html.twig', [
            'form' => $form->createView(),
        ]); 
    }

    /**
     * @Route("/categorie/add", name="categorie_add", methods="POST")
     */
    public function add(Request $rq, EMI $em)
    {
        // Les données du $_POST peuvent être récupérés avec 
        // $request->request->get("nomDuFormulaire") qui est un array (comme $_POST)
        try{
            // Créer le formulaire
            $form = $this->createForm(CategorieType::class);
            
            // Passer la requête HTTP au formulaire
            $form->handleRequest($rq);

            if( $form->isSubmitted() && $form->isValid() ){
                // Récupérer les données envoyées (si le formulaire est lié à une entité, getData() renvoie un
                // objet de la classe de cette entité )
                $data = $form->getData();

                $data->setDateEnregistrement( date_create("now") );


                $em->persist($data);
                $em->flush();
                $this->addFlash('success', 'La catégorie  a bien été enregistrée !');
                return $this->redirectToRoute("accueil");
            }
            elseif(!$form->isValid()) {
                $this->addFlash('error', 'Les données du formulaire ne sont pas valides');
                // $form = $this->createForm(CategorieType::class, $form->getData());
                return $this->render('categorie/form.html.twig', [
                    'form' => $form->createView(),
                ]); 
            }


        } catch (\Doctrine\DBAL\DBALException $e) {
            dd($e->getMessage(), $e);
        }
         catch (\Exception $e){
            dd($e->getMessage(), $e);
        }

    }
    /**
     * @Route("categorie/modify/{id}", name="categorie_modify")
     */
    public function modify(Request $rq, CR $repo, $id)
    {
        $categorieAmodifier = $repo->find($id);
        $form = $this->createForm(CategorieType::class, $categorieAmodifier);
        return $this->render('categorie/form.html.twig', [
            'form' => $form->createView(),
        ]); 
    }

    
}
