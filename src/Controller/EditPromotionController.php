<?php

namespace App\Controller;

use App\Form\PromoType;
use App\Entity\Apprenant;
use App\Entity\Formation;
use App\Entity\Promotion;
use App\Form\FormationType;
use App\Form\PromotionType;
use App\Repository\ApprenantRepository;
use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditPromotionController extends AbstractController
{
    

    /**
     * Afficher toutes les promotions avec la possibilité de modifier et de supprimer
     * 
     * @Route("/editor/promo_liste", name="editor_promo_liste")
     */
    public function promo_list(PromotionRepository $repo)
    {
        $promotion = $repo->findBy(array(), array('Annee' => 'asc'));
        return $this->render('editor/promotion/_promo_liste.html.twig', [
            'promotions' => $promotion
        ]);
    }

    /**
     * Afficher une promotion
     * 
     * @Route("/editor/promo_show/{id}", name="editor_promo_show")
     */
    public function promotion_show(Promotion $promotion)
    {
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
        return $this->render('editor/promotion/_promo_new.html.twig', [
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
        // $promotion = $repo->findBy(array(), array('Annee' => 'asc'));
        $promotion = $repo->selectPromotion(new \DateTime);
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

        return $this->render('editor/promotion/_promo_attr_appr.html.twig', [
            'promotions' => $promotion,
            // 'form' => $form->createView()
        ]);
    }

    
    /**
     * Créer un nouveau apprenant et créer de lui un utilisateur, puis l'attribuer à une promotion
     * 
     * @Route("/editor/promo_attr_many", name="editor_promo_attr_many")
     */
    public function promo_attr_apprs()
    {

        return $this->render('editor/promotion/_promo_attr_many.html.twig');
    }

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
        return $this->render('editor/promotion/promotion_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * supprimer une promotion
     * 
     * @Route("/admin/delete_promotion/{id}", name="editor_delete_promotion")
     */
    public function delete_promotion($id, PromotionRepository $repo)
    {
        $promotion = $repo->find($id);

        $manager = $this->getDoctrine()->getManager();

        $manager->remove($promotion);
        $manager->flush();

        $this->addFlash('danger', 'Une promotion a été supprimée!');
        return $this->redirectToRoute('editor_promo_liste');
    }


    /**
     * afficher un apprenant
     * 
     * @Route("/editor/pro_apprenant_show/{id}", name="editor_pro_apprenant_show")
     */
    public function pro_apprenant_show(Apprenant $apprenant)
    {
        return $this->render('editor/promotion/apprenant_show.html.twig', [
            'apprenant' => $apprenant
        ]);
    }


    /**
     * afficher les formations et créer une formation
     * 
     * @Route("/editor/formation", name="editor_formation")
     */
    public function formation(EntityManagerInterface $em, FormationRepository $repo, Request $request)
    {
        $newFormation = new Formation();
        $form = $this->createForm(FormationType::class, $newFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $Manager = $this->getDoctrine()->getManager();
            $em->persist($newFormation);
            $em->flush();

            $this->addFlash('success', 'Une formation a été créée!');
        }

        $formation = $repo->findBy(array(), array('Intitule' => 'asc'));
        return $this->render('editor/formation/formation.html.twig', [
            'formations' => $formation,
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
        return $this->render('editor/formation/formation_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * supprimer une formation
     * 
     * @Route("/admin/delete_formation/{id}", name="editor_delete_formation")
     */
    public function delete_formation($id, FormationRepository $repo, PromotionRepository $repoPromotion)
    {
        $formation = $repoPromotion->findOneBy(['Formation' => $id]);
        if ($formation) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette formation qui est liée au moins à une promotion !');
            return $this->redirectToRoute('editor_formation');
        } else {

            $formation = $repo->find($id);

            $manager = $this->getDoctrine()->getManager();

            $manager->remove($formation);
            $manager->flush();

            $this->addFlash('danger', 'Une formation a été supprimée!');
            return $this->redirectToRoute('editor_formation');
        }
    }

}
