<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Formation;
use App\Entity\Promotion;
use App\Form\FormationType;
use App\Form\PromotionType;
use App\Form\ApprenantType;
use App\Repository\ApprenantRepository;
use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
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
    public function promo_attr_appr(UserRepository $repoUser, PromotionRepository $repo, Request $request, ApprenantRepository $repoAppr, UserPasswordEncoderInterface $encoder)
    {
        $promotion = $repo->findBy(array(), array('Annee' => 'asc'));

        $newApprenant = new Apprenant();

        $form = $this->createForm(ApprenantType::class, $newApprenant);
        $form->handleRequest($request);

        
        // $email=$newApprenant->getEmail();
        // $user= $repoUser->findBy(['email' => $email]);
        // if ($user){
      


        //     dd($request);
        //     $this->addFlash('warning', 'Cet email existe déjà!');
        // }else{
            
            if ($form->isSubmitted() && $form->isValid()) {
                // dd($request);
    
                $Manager = $this->getDoctrine()->getManager();
    
                $mdp = strtolower($newApprenant->getNom() . $newApprenant->getPrenom());
    
                $mdp_hash = $encoder->encodePassword($newApprenant, $mdp);
    
                $newApprenant->setPassword($mdp_hash);
                try{
                    $Manager->persist($newApprenant);
        
                    $Manager->flush();
                    $this->addFlash('success', 'Un utilisateur a été créé!');
                    $this->addFlash('success', 'Un apprenant a été attribué à une promotion!');

                }catch(Exception $e){
                $this->addFlash('danger', 'Cet email existe déjà!');
                }
    
    
            }
        // }
        return $this->render('editor/promotion/_promo_attr_appr.html.twig',[
            'promotions' => $promotion,
            'form' => $form->createView()
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
     * afficher les promotions et en créer une
     * 
     * @Route("/editor/promotion", name="editor_promotion")
     */
    public function promotion(PromotionRepository $repo, ApprenantRepository $repoApprenant, Request $request)
    {
        // $newPromotion = new Promotion();
        // $form = $this->createForm(PromotionType::class,$newPromotion);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()){
        //     $Manager = $this->getDoctrine()->getManager();
        //     $Manager->persist($newPromotion);
        //     $Manager->flush();

        //     $this->addFlash('success', 'Une promotion a été créée!');
        // }

        // $apprenant = $repoApprenant->findBy(array(), array('Nom' => 'asc'));
        // $promotion=$repo->findBy(array(), array('Annee' => 'asc'));
        return $this->render('editor/promotion.html.twig',[
            // 'promotions'=>$promotion,
            // 'apprenants' => $apprenant,
            // 'form' => $form->createView()
        ]);
    }

    



    

    /**
     * afficher les formations et créer une formation
     * 
     * @Route("/editor/formation", name="editor_formation")
     */
    public function formation(FormationRepository $repo, Request $request)
    {
        $newFormation = new Formation();
        $form = $this->createForm(FormationType::class,$newFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $Manager = $this->getDoctrine()->getManager();
            $Manager->persist($newFormation);
            $Manager->flush();

            $this->addFlash('success', 'Une formation a été créée!');
        }

        $formation = $repo->findBy(array(), array('Intitule' => 'asc'));
        return $this->render('editor/formation.html.twig', [
            'formations' => $formation,
            'form' => $form->createView()
        ]);
    }


    /**
     * afficher les apprenants et ajouter un nouveau et créer un nouveau utilisateur dans User class
     * 
     * @Route("/editor/apprenant", name="editor_apprenant")
     */
    public function apprenant(Request $request, ApprenantRepository $repo, UserPasswordEncoderInterface $encoder)
    {
        $newApprenant = new Apprenant();
        
        $form = $this->createForm(ApprenantType::class, $newApprenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $Manager = $this->getDoctrine()->getManager();

            $mdp = strtolower($newApprenant->getNom() . $newApprenant->getPrenom());

            $mdp_hash = $encoder->encodePassword($newApprenant, $mdp);

            $newApprenant->setPassword($mdp_hash);

            $Manager->persist($newApprenant);

            $Manager->flush();

            $this->addFlash('success', 'Un utilisateur a été créée!');
            $this->addFlash('success', 'Un apprenant a été attribué à une promotion!');
                
        }
        
        $apprenant = $repo->findAll();

        return $this->render('editor/apprenant.html.twig', [
            'apprenants' => $apprenant,
            'form' => $form->createView()
        ]);
    }




    

}
