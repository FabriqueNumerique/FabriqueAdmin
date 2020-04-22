<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Formation;
use App\Entity\Promotion;
use App\Entity\Reseau;
use App\Entity\User;
use App\Form\FormationType;
use App\Form\PromotionType;
use App\Form\ApprenantType;
use App\Repository\ApprenantRepository;
use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
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
     * @Route("/editor/promo_liste", name="editor_promo_liste")
     */
    public function promo_list(PromotionRepository $repo)
    {
        $promotion = $repo->findBy(array(), array('Annee' => 'asc'));
        return $this->render('admin/_promo_liste.html.twig',[
           'promotions' => $promotion 
        ]);
    }

    /**
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
        return $this->render('admin/_promo_new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editor/promo_attr_appr", name="editor_promo_attr_appr")
     */
    public function promo_attr_appr(PromotionRepository $repo, Request $request, ApprenantRepository $repoAppr, UserRepository $repoUser, UserPasswordEncoderInterface $encoder)
    {
        $promotion = $repo->findBy(array(), array('Annee' => 'asc'));

        $user = new User();
        $apprenant = new Apprenant();
        // $reseau=new Reseau();
        // $reseau->setNom('facebook')->setLien('face');
        // $newApprenant->addReseaux($reseau);

        $form = $this->createForm(ApprenantType::class, $apprenant);
        $form->handleRequest($request);
        $email = $apprenant->getEmail();

        if ($form->isSubmitted() && $form->isValid()) {
            // on teste si cet email existe déjà dans la table apprenant
            if (($repoAppr->findOneBy(['Email' => $email]))) {

                $this->addFlash('warning', 'Cet apprenant existe déjà !');

                // s'il n'existe pas il va l'ajouter à la table apprenant
            } else {

                $Manager = $this->getDoctrine()->getManager();
                $Manager->persist($apprenant);

                $email = $apprenant->getEmail();

                //    et s'il n'existe pas dans la table user, il va l'ajouter
                if (!($repoUser->findOneBy(['email' => $email]))) {

                    $mdp = strtolower($apprenant->getNom() . $apprenant->getPrenom());
                    $mdp_hash = $encoder->encodePassword($user, $mdp);
                    $user->setEmail($email)
                        ->setPassword($mdp_hash);
                    $Manager->persist($user);
                    $this->addFlash('success', 'Un utilisateur a été créée!');
                }
                $Manager->flush();
                $this->addFlash('success', 'Un apprenant a été créée!');
            }
        }
        return $this->render('admin/_promo_attr_appr.html.twig',[
            'promotions' => $promotion,
            'form' => $form->createView()
        ]);
    }

    


    /**
     * afficher les promotions et en créer une
     * 
     * @Route("/editor/promotion", name="editor_promotion")
     */
    public function promotion(PromotionRepository $repo, ApprenantRepository $repoApprenant, Request $request)
    {
        $newPromotion = new Promotion();
        $form = $this->createForm(PromotionType::class,$newPromotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $Manager = $this->getDoctrine()->getManager();
            $Manager->persist($newPromotion);
            $Manager->flush();

            $this->addFlash('success', 'Une promotion a été créée!');
        }

        $apprenant = $repoApprenant->findBy(array(), array('Nom' => 'asc'));
        $promotion=$repo->findBy(array(), array('Annee' => 'asc'));
        return $this->render('editor/promotion.html.twig',[
            'promotions'=>$promotion,
            'apprenants' => $apprenant,
            'form' => $form->createView()
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
    public function apprenant(Request $request, ApprenantRepository $repo, UserRepository $repoUser, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $newApprenant = new Apprenant();
        // $reseau=new Reseau();
        // $reseau->setNom('facebook')->setLien('face');
        // $newApprenant->addReseaux($reseau);

        $form = $this->createForm(ApprenantType::class, $newApprenant);
        $form->handleRequest($request);
        $email = $newApprenant->getEmail();

        if ($form->isSubmitted() && $form->isValid()) {
            // on teste si cet email existe déjà dans la table apprenant
            if (($repo->findOneBy(['Email' => $email]))) {

                $this->addFlash('warning', 'Cet apprenant existe déjà !');

                // s'il n'existe pas il va l'ajouter à la table apprenant
                }else{

                    $Manager = $this->getDoctrine()->getManager();
                    $Manager->persist($newApprenant);
                    
                    $email = $newApprenant->getEmail();

                //    et s'il n'existe pas dans la table user, il va l'ajouter
                    if (!($repoUser->findOneBy(['email'=> $email]))){
        
                        $mdp = strtolower($newApprenant->getNom().$newApprenant->getPrenom());
                        $mdp_hash=$encoder->encodePassword($user,$mdp);
                        $user->setEmail($email)
                            ->setPassword($mdp_hash);
                        $Manager->persist($user);
                        $this->addFlash('success', 'Un utilisateur a été créée!');
                    }
                    $Manager->flush();
                    $this->addFlash('success', 'Un apprenant a été créée!');
                }
        }
        $apprenant = $repo->findAll();

        return $this->render('editor/apprenant.html.twig', [
            'apprenants' => $apprenant,
            'form' => $form->createView()
        ]);
    }




    

}
