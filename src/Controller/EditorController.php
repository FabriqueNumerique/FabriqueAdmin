<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Formation;
use App\Entity\Promotion;
use App\Entity\Reseau;
use App\Form\FormationType;
use App\Form\PromotionType;
use App\Form\ApprenantType;
use App\Repository\ApprenantRepository;
use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditorController extends AbstractController
{
    

    /**
     * @Route("/editor/dashbord", name="editor_dashbord")
     */
    public function dashbord()
    {
       
        return $this->render('editor/dashbord.html.twig');
    }


    /**
     * Afficher toutes les promotions avec la possibilité de modifier et de supprimer
     * 
     * @Route("/editor/promo_liste", name="editor_promo_liste")
     */
    public function promo_list(PromotionRepository $repo)
    {
        $promotion = $repo->findBy(array(), array('Annee' => 'asc'));
        return $this->render('editor/promotion/_promo_liste.html.twig',[
           'promotions' => $promotion 
        ]);
    }


    /**
     * Afficher une promotion
     * 
     * @Route("/editor/promo_show/{id}", name="editor_promo_show")
     */
    public function promotion_show(PromotionRepository $repo, $id)
    {
        $promotion = $repo->find($id);
        // dd($promotion);
        return $this->render('editor/promotion/promo_show.html.twig', [
            'promotions' => $promotion
        ]);
    }


    /**
     * Créer une promotion
     * 
     * @Route("/editor/promo_new", name="editor_promo_new")
     */
    public function promo_new(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $Manager = $this->getDoctrine()->getManager();
            $Manager->persist($promotion);
            $Manager->flush();

            $this->addFlash('success', 'Une promotion a été créée!');
        }
        return $this->render('editor/promotion/_promo_new.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * Créer un nouveau apprenant et créer de lui un utilisateur, puis l'attribuer à une promotion
     * 
     * @Route("/editor/promo_attr_appr", name="editor_promo_attr_appr")
     */
    public function promo_attr_appr(PromotionRepository $repo, Request $request, ApprenantRepository $repoAppr, UserPasswordEncoderInterface $encoder)
    {
        $promotion = $repo->findBy(array(), array('Annee' => 'asc'));

        $newApprenant = new Apprenant();

        // $form = $this->createForm(ApprenantType::class, $newApprenant);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

        //     $Manager = $this->getDoctrine()->getManager();

        //     $mdp = strtolower($newApprenant->getNom() . $newApprenant->getPrenom());

        //     $mdp_hash = $encoder->encodePassword($newApprenant, $mdp);

        //     $newApprenant->setPassword($mdp_hash);

        //     try {
        //         $Manager->persist($newApprenant);

        //         $Manager->flush();
        //         $this->addFlash('success', 'Un utilisateur a été créé!');
        //         $this->addFlash('success', 'Un apprenant a été attribué à une promotion!');
        //     } catch (Exception $e) {
        //         $this->addFlash('danger', 'Cet email existe déjà!');
        //     }

        // }

        return $this->render('editor/promotion/_promo_attr_appr.html.twig',[
            'promotions' => $promotion,
            // 'form' => $form->createView()
        ]);
    }


    /**
     * Créer un nouveau apprenant et créer de lui un utilisateur, puis l'attribuer à une promotion
     * 
     * @Route("/editor/promo_attr_many", name="editor_promo_attr_many")
     */
    public function promo_attr_apprs(){

        return $this->render('editor/promotion/_promo_attr_many.html.twig');

    }


    
    /**
     * afficher les formations et créer une formation
     * 
     * @Route("/editor/formation", name="editor_formation")
     */
    public function formation(EntityManagerInterface $em, FormationRepository $repo, Request $request)
    {
        $newFormation = new Formation();
        $form = $this->createForm(FormationType::class,$newFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            // $Manager = $this->getDoctrine()->getManager();
            $em->persist($newFormation);
            $em->flush();

            $this->addFlash('success', 'Une formation a été créée!');
        }

        $formation = $repo->findBy(array(), array('Intitule' => 'asc'));
        return $this->render('editor/formation.html.twig', [
            'formations' => $formation,
            'form' => $form->createView()
        ]);
    }


    /**
     * afficher les apprenants 
     * 
     * @Route("/editor/apprenant_liste", name="editor_apprenant_liste")
     */
    public function apprenant_liste(ApprenantRepository $repo)
    {
        $apprenant = $repo->findAll();
        return $this->render('editor/apprenant/apprenant_liste.html.twig', [
            'apprenants' => $apprenant
        ]);

    }

    /**
     * ajouter un nouveau apprenant et créer un nouveau utilisateur dans User class
     * 
     * @Route("/editor/apprenant_new", name="editor_apprenant_new")
     */
    public function apprenant_new(Request $request, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader)
    {
        $newApprenant = new Apprenant();
        $Manager = $this->getDoctrine()->getManager();
        // $reseau = new Reseau();
        // $reseau->setNom('instagram')->setLien('instagram.com/mouna.ed');
        // $newApprenant->addReseaux($reseau);


        $form = $this->createForm(ApprenantType::class, $newApprenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
       


            $brochureFile = $form['brochure']->getData();
            // dd($brochureFile);
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $newApprenant->setAvatar($brochureFileName);
            }
            $mdp = strtolower($newApprenant->getNom() . $newApprenant->getPrenom());

            $mdp_hash = $encoder->encodePassword($newApprenant, $mdp);

            $newApprenant->setPassword($mdp_hash);

            try {
                $Manager->persist($newApprenant);

                $Manager->flush();
                $this->addFlash('success', 'Un utilisateur a été créé!');
                $this->addFlash('success', 'Un apprenant a été attribué à une promotion!');
                return $this->redirectToRoute('editor_apprenant_liste');
            } catch (Exception $e) {
                $this->addFlash('danger', 'Cet email existe déjà!');
            }
        }
        
        return $this->render('editor/apprenant/appreant_new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * afficher un apprenant
     * 
     * @Route("/editor/apprenant_show/{id}", name="editor_apprenant_show")
     */
    public function apprenant_show(ApprenantRepository $repo, $id)
    {
        $apprenant = $repo->find($id);
        return $this->render('editor/apprenant/apprenant_show.html.twig', [
            'apprenant' => $apprenant
        ]);
    }


    /**
     * afficher un apprenant
     * 
     * @Route("/editor/pro_apprenant_show/{id}", name="editor_pro_apprenant_show")
     */
    public function pro_apprenant_show(ApprenantRepository $repo, $id)
    {
        $apprenant = $repo->find($id);
        return $this->render('editor/promotion/apprenant_show.html.twig', [
            'apprenant' => $apprenant
        ]);
    }    
}
