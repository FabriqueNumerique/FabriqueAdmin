<?php

namespace App\Controller;

use Exception;
use App\Form\PromoType;
use App\Entity\Apprenant;
use App\Entity\Formation;
use App\Entity\Promotion;
use App\Form\ApprenantType;
use App\Form\FormationType;
use App\Form\ProApprType;
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
     * @Route("/editor/promo_liste{page<\d+>?1}", name="editor_promo_liste")
     */
    public function promo_list(PromotionRepository $repo, $page)
    {
        $limit = 5;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);

        return $this->render('editor/promotion/promo_liste.html.twig', [
            'promotions' => $repo->findBy([], ['Annee' => 'desc'], $limit, $start),
            'pages' => $pages,
            'page' => $page
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
        $Manager = $this->getDoctrine()->getManager();
        $promotion = new Promotion();
        $form = $this->createForm(PromoType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on change le statut des apprenants qui ont été attribués
            $apprenants= $form->getData()->getApprenants();

            $dateDebut = $form->getData()->getdateDebut();
            $dateFin = $form->getData()->getdateFin();

            if ($dateFin > $dateDebut){
                foreach ($apprenants as $apprenant){
                    $apprenant->setStatus('old');
                    $Manager->persist($apprenant);
                }
    
                $Manager->persist($promotion);
                $Manager->flush();
    
                $this->addFlash('success', "La promotion de l'année {$promotion->getAnnee()} a été créée!");
                return $this->redirectToRoute('editor_promo_liste');
            }
            else{
                $this->addFlash('danger', "La date de fin doit être supérieure à la date de début !"); 
            }
        }
        return $this->render('editor/promotion/promo_new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Créer un nouveau apprenant et créer de lui un utilisateur, puis l'attribuer à une promotion
     * 
     * @Route("/editor/promo_attr_appr", name="editor_promo_attr_appr")
     */
    public function promo_attr_appr(PromotionRepository $repo, Request $request, ApprenantRepository $repoAppr)
    {
        

        return $this->render('editor/promotion/promo_attr_appr.html.twig', [
            // 'promotions' => $promotion,
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

        return $this->render('editor/promotion/promo_attr_many.html.twig');
    }

    /**
     * modifier une promotion
     * 
     * @Route("/editor/edit_promotion/{id}", name="editor_edit_promotion")
     */
    public function edit_promotion(Promotion $promotion, PromotionRepository $repo, Request $request)
    {
        $Manager = $this->getDoctrine()->getManager();
        // $newPromotion = $repo->find($id);
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // on change le statut des apprenants qui ont été attribués
            $apprenants = $form->getData()->getApprenants();
            $dateDebut = $form->getData()->getdateDebut();
            $dateFin = $form->getData()->getdateFin();
            
            if ($dateFin > $dateDebut){

                foreach ($apprenants as $apprenant) {
                    $apprenant->setStatus('old');
                    $Manager->persist($apprenant);
                }
    
                $Manager->persist($promotion);
                $Manager->flush();
    
                $this->addFlash('warning', "La promotion de l'année {$promotion->getAnnee()} intitulée / {$promotion->getFormation()->getIntitule()} / a été modifiée!");
                return $this->redirectToRoute('editor_promo_liste');
            }
            else{
                $this->addFlash('danger', "La date de fin doit être supérieure à la date de début !"); 
            }
            
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
     * @Route("/editor/formation{page<\d+>?1}", name="editor_formation")
     */
    public function formation(FormationRepository $repo, $page)
    {
        $limit = 5;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);

        $formation = $repo->findBy(array(), array('Intitule' => 'asc'));
        return $this->render('editor/formation/formation_liste.html.twig', [
            'formations' => $repo->findBy([], ['Intitule' => 'asc'], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }


    /**
     * afficher les formations et créer une formation
     * 
     * @Route("/editor/formation_new", name="editor_formation_new")
     */
    public function formation_new(EntityManagerInterface $em, Request $request)
    {
        $newFormation = new Formation();
        $form = $this->createForm(FormationType::class, $newFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($newFormation);
            $em->flush();

            $this->addFlash('success', 'Une formation a été créée!');
            return $this->redirectToRoute('editor_formation');
        }

        
        return $this->render('editor/formation/formation_new.html.twig', [
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
