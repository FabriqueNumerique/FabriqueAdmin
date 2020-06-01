<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\PromoAppre;
use App\Entity\Promotion;
use App\Form\PromoAppreType;
use App\Form\PromotionType;
use App\Repository\ApprenantRepository;
use App\Repository\PromoAppreRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EditPromotionController extends AbstractController
{

    /**
     * Afficher toutes les promotions avec la possibilité de modifier et de supprimer
     * le parametre 'page': le \d+ = nombre, ? = optionnel, 1 = valeur par defaut
     * 
     * @Route("/editor/promotion{page<\d+>?1}", name="editor_promo_liste")
     */
    public function promo_list(PromotionRepository $repo, $page)
    {
        $limit = 10;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);

        return $this->render('editor/promotion/promo_liste.html.twig', [
            'promotions' => $repo->findBy([], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }


    /**
     * Créer une promotion
     * 
     * @Route("/editor/promotion/new", name="editor_promo_new")
     */
    public function promo_new(Request $request, ApprenantRepository $repo)
    {
        $Manager = $this->getDoctrine()->getManager();

        
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère les apprenants ajoutés à cette nouvelle promotion
            // $apprenants= $form->getData()->getApprenants();
            $apprenants = $form['apprenants']->getData();


            $dateDebut = $form->getData()->getdateDebut();
            $dateFin = $form->getData()->getdateFin();

            // ici une contrainte concernant la date de début et de fin
            if ($dateFin > $dateDebut){

                $Manager->persist($promotion);
                
                // enregistrer les apprenants ajoutés dans l"entité PromoAppre, s'il y en a
                foreach ($apprenants as $apprenant){
                    $promoAppre = new PromoAppre();
                    $promoAppre ->setPromotion($promotion)
                                ->setApprenant($apprenant);

                    // récupérer l'objet apprenant et changer son statut
                    $apprenatStatus = $repo->find($apprenant);
                    $apprenatStatus->setStatus('old');

                    $Manager->persist($apprenatStatus);
                    $Manager->persist($promoAppre);
                }

                $Manager->flush();

                $this->addFlash('success', "La promotion {$promotion->getFormation()} de l'année {$promotion->getAnnee()} a été créée!");
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
     * modifier une promotion
     * 
     * @Route("/editor/promotion/edit/{id}", name="editor_edit_promotion")
     */
    public function edit_promotion(Promotion $promotion, Request $request, ApprenantRepository $repo)
    {
       
        $Manager = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // on récupère les apprenants ajoutés à cette nouvelle promotion
            // $apprenants= $form->getData()->getApprenants();
            $apprenants = $form['apprenants']->getData();
            
            $dateDebut = $form->getData()->getdateDebut();
            $dateFin = $form->getData()->getdateFin();

            if ($dateFin > $dateDebut){    
    
                $Manager->persist($promotion);

                // enregistrer les apprenants ajoutés dans l"entité PromoAppre, s'il y en a
                foreach ($apprenants as $apprenant) {
                    $promoAppre = new PromoAppre();
                    $promoAppre ->setPromotion($promotion)
                                ->setApprenant($apprenant);

                    // récupérer l'objet apprenant et changer son statut            
                    $apprenatStatus=$repo->find($apprenant);
                    $apprenatStatus->setStatus('old');

                    $Manager->persist($apprenatStatus);
                    $Manager->persist($promoAppre);
                    
                }

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
     * Afficher une promotion avec ses apprenants
     * 
     * @Route("/editor/promotion/show/{id}", name="editor_show_promotion")
     */
    public function show_promotion(PromoAppreRepository $repo, Promotion $promotion)
    {
        
        return $this->render('editor/promotion/promo_show.html.twig',[
            'promotion'=>$promotion,
            'apprenants'=> $repo->findBy(['promotion' => $promotion])
        ]);
    }

    /**
     * supprimer une promotion
     * 
     * @Route("/editor/promotion/delete/{id}", name="editor_delete_promotion")
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
     * retirer un apprenant d'une promotion
     * 
     * @Route("/editor/promotion/retirer/{id}", name="editor_retirer_apprenant")
     */
    public function retirer_apprenant(PromoAppre $promoAppre)
    {
        $manager = $this->getDoctrine()->getManager();

        //récupérer l'apprenant et changer son statut
        $apprenant=$promoAppre->getApprenant();

        $apprenant->setStatus('new');
        
        $manager->persist($apprenant);

        //supprimer l'apprenant de l'entité PromoAppre
        $manager->remove($promoAppre);
        $manager->flush();

        $this->addFlash('danger', "L'apprenant {$promoAppre->getApprenant()} a été retiré de la promotion {$promoAppre->getPromotion()}");
        return $this->redirectToRoute('editor_promo_liste');
    }

    

    /**
     * afficher un apprenant
     * 
     * @Route("/editor/pro_apprenant_show/{id}", name="editor_pro_apprenant_show")
     */
    public function pro_apprenant_show(Apprenant $apprenant, PromoAppreRepository $repo)
    {
        return $this->render('editor/promotion/apprenant_show.html.twig', [
            'all' => $repo->findBy(['apprenant' => $apprenant])
        ]);
    }


    /**
     * Attribuer un apprenant
     * 
     * @Route("/editor/promotion/attr", name="editor_attr")
     */

    public function editor_attr(Request $request, EntityManagerInterface $manager, ApprenantRepository $repo)
    {
        $promo_appre = new PromoAppre();
        $form = $this->createForm(PromoAppreType::class, $promo_appre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //récupérer l'apprenant et changer son statut
            $apprenant = $form['apprenant']->getData();
            $newApprenant = $repo->find($apprenant);

            $newApprenant->setStatus('old');
            $manager->persist($newApprenant);

            $manager->persist($promo_appre);
            $manager->flush();
            $this->addFlash('success', "L'apprenant - {$newApprenant->getFullname()} - 
            a été attribué à la promotion - {$promo_appre->getPromotion()}");
            return $this->redirectToRoute('editor_promo_liste');
        }
        return $this->render('editor/promotion/promo_attr.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
