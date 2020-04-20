<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Promotion;
use App\Form\FormationType;
use App\Form\PromotionType;
use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/editor/edit_promotion/{id}", name="editor_edit_promotion")
     */
    public function edit_promotion($id,PromotionRepository $repo,Request $request)
    {
        $newPromotion = $repo->find($id);
        $form = $this->createForm(PromotionType::class, $newPromotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Manager = $this->getDoctrine()->getManager();
            
            $Manager->flush();

            $this->addFlash('warning', 'Une promotion a été modifiée!');
            return $this->redirectToRoute('editor_promotion');
        }
        return $this->render('editor/promotion_edit.html.twig', [
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
     * @Route("/editor/edit_formation/{id}", name="editor_edit_formation")
     */
    public function edit_formation($id, FormationRepository $repo, Request $request)
    {
        $newFormation = $repo->find($id);
        $form = $this->createForm(FormationType::class, $newFormation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Manager = $this->getDoctrine()->getManager();

            $Manager->flush();

            $this->addFlash('warning', 'Une formation a été modifiée!');
            return $this->redirectToRoute('editor_formation');
        }
        return $this->render('editor/formation_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }



    

}
