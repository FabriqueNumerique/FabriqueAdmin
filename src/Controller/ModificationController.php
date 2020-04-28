<?php

namespace App\Controller;

use App\Form\FormationType;
use App\Form\PromotionType;
use App\Form\ApprenantType;
use App\Form\PromoType;
use App\Repository\ApprenantRepository;
use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ModificationController extends AbstractController
{


    /**
     * modifier une promotion
     * 
     * @Route("/editor/edit_promotion/{id}", name="editor_edit_promotion")
     */
    public function edit_promotion($id, PromotionRepository $repo, Request $request)
    {
        $newPromotion = $repo->find($id);
        $form = $this->createForm(PromoType::class, $newPromotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Manager = $this->getDoctrine()->getManager();

            $Manager->flush();

            $this->addFlash('warning', 'Une promotion a été modifiée!');
            // return $this->redirectToRoute('editor_promotion');
        }
        return $this->render('modification/promotion_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * modifier une formation
     * 
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
        return $this->render('modification/formation_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * modifier un apprenant
     * 
     * @Route("/editor/edit_apprenant/{id}", name="editor_edit_apprenant")
     */
    public function edit_apprenant($id, ApprenantRepository $repo, Request $request, FileUploader $fileUploader)
    {
        $newApprenant = $repo->find($id);
        $form = $this->createForm(ApprenantType::class, $newApprenant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form['brochure']->getData();
            // dd($brochureFile);
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $newApprenant->setAvatar($brochureFileName);
            }

            $Manager = $this->getDoctrine()->getManager();
            
            $Manager->flush();
            // dd($newApprenant);

            $this->addFlash('warning', 'Un apprenant a été modifié!');
            // return $this->redirectToRoute('editor_apprenant');
        }
        return $this->render('modification/apprenant_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
