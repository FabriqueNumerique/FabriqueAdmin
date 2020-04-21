<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Formation;
use App\Entity\Promotion;
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
    public function dashbord(UserRepository $repo)
    {
        $user = $repo->findAll();
        return $this->render('editor/dashbord.html.twig', [
            'users' => $user
        ]);
    }


    /**
     * @Route("/editor/promotion", name="editor_promotion")
     */
    public function promotion(PromotionRepository $repo, Request $request)
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

        $promotion=$repo->findAll();
        return $this->render('editor/promotion.html.twig',[
            'promotions'=>$promotion,
            'form' => $form->createView()
        ]);
    }

    



    

    /**
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

        $formation = $repo->findAll();
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
        $form = $this->createForm(ApprenantType::class, $newApprenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $Manager = $this->getDoctrine()->getManager();
            $Manager->persist($newApprenant);
            
            $email = $newApprenant->getEmail();
            // dd($newApprenant);
            // if (!($repo->findOneBy(['email'=> $email]))){
            //     dd($email);
            // }
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

        $apprenant = $repo->findAll();

        return $this->render('editor/apprenant.html.twig', [
            'apprenants' => $apprenant,
            'form' => $form->createView()
        ]);
    }



    

}
