<?php

namespace App\Controller;


use App\Form\PromoType;
use App\Entity\Apprenant;
use App\Entity\Formation;
use App\Entity\Promotion;
use App\Form\AttrType;
use App\Form\FormationType;
use App\Form\PromotionType;
use App\Repository\ApprenantRepository;
use App\Repository\FormationRepository;
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
            'promotions' => $repo->findBy([], ['DateFin' => 'desc'], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }



    /**
     * Créer une promotion
     * 
     * @Route("/editor/promotion/new", name="editor_promo_new")
     */
    public function promo_new(Request $request)
    {
        $Manager = $this->getDoctrine()->getManager();
        $promotion = new Promotion();
        $form = $this->createForm(PromoType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère les apprenants ajoutés à cette nouvelle promotion
            $apprenants= $form->getData()->getApprenants();

            $dateDebut = $form->getData()->getdateDebut();
            $dateFin = $form->getData()->getdateFin();

            // ici une contrainte concernant la date de début et de fin
            if ($dateFin > $dateDebut){

                // on change le statut des apprenants qui ont été attribués à cette nouvelle promotion
                // dans ce formulaire nous avons la possibilité d'attribuer un ou plusieurs apprenants à la promotion en question
                foreach ($apprenants as $apprenant){
                    $apprenant->setStatus('old');
                    $Manager->persist($apprenant);
                }
    
                $Manager->persist($promotion);
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
    public function edit_promotion(Promotion $promotion, Request $request)
    {
        $oldApprenants=$promotion->getApprenants();
        $tab1 = [];
        foreach ($oldApprenants as $app){
            // dump($app);
            array_push($tab1, $app);
        }
        // dump($tab1);
        $Manager = $this->getDoctrine()->getManager();
        // $newPromotion = $repo->find($id);
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // on change le statut des apprenants qui ont été attribués
            $apprenants = $form->getData()->getApprenants();
            $dateDebut = $form->getData()->getdateDebut();
            $dateFin = $form->getData()->getdateFin();
            $tab2=[];
            if ($dateFin > $dateDebut){
                foreach ($apprenants as $apprenant) {
                    // $apprenant->setStatus('old');
                    // $Manager->persist($apprenant);
                    array_push($tab2,$apprenant);
                    
                }
                $result=array_diff($tab1, $tab2);
                if (!empty($result)){
                    foreach ($result as $row){
                        $row->setStatus('new');
                        $Manager->persist($row);
                       
                    }
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
     * @Route("/admin/promotion/delete/{id}", name="editor_delete_promotion")
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
        $limit = 10;
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
     * @Route("/editor/formation/new", name="editor_formation_new")
     */
    public function formation_new(EntityManagerInterface $em, Request $request)
    {
        $newFormation = new Formation();
        $form = $this->createForm(FormationType::class, $newFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($newFormation);
            $em->flush();

            $this->addFlash('success', "La formation intitulée - {$newFormation->getIntitule()} - a été créée!");
            return $this->redirectToRoute('editor_formation');
        }

        
        return $this->render('editor/formation/formation_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * modifier une formation
     * 
     * @Route("/editor/formation/edit/{id}", name="editor_edit_formation")
     */
    public function edit_formation($id, FormationRepository $repo, Request $request)
    {
        $formation = $repo->find($id);
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Manager = $this->getDoctrine()->getManager();

            $Manager->flush();

            $this->addFlash('warning', "La formation intitulée - {$formation->getIntitule()} - a été modifiée!");
            return $this->redirectToRoute('editor_formation');
        }
        return $this->render('editor/formation/formation_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * supprimer une formation
     * 
     * @Route("/admin/formation/delete/{id}", name="editor_delete_formation")
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

            $this->addFlash('danger', "La formation intitulée - {$formation->getIntitule()} - a été supprimée!");
            return $this->redirectToRoute('editor_formation');
        }
    }

    /**
     * attribute a learner
     * 
     * @Route("/admin/promotion/attr", name="editor_attr")
     */
    public function editor_attr(Request $request, EntityManagerInterface $manager,PromotionRepository $repo1, ApprenantRepository $repo2)
    {
        
        $form=$this->createForm(AttrType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $promotion=$form['Promotion']->getData();
            $apprenant = $form['Apprenant']->getData();
            $newPromotion=$repo1->find($promotion);
            $newApprenant = $repo2->find($apprenant);
            $newPromotion->addApprenant($apprenant);
            $newApprenant->setStatus('old');
            $manager->persist($newApprenant);
            $manager->persist($newPromotion);
            $manager->flush();
            $this->addFlash('success', "L'apprenant - {$newApprenant->getfullName()} - 
            a été attribué à la promotion - {$newPromotion->getFormation()} de l'année {$newPromotion->getAnnee()} ");
            return $this->redirectToRoute('editor_promo_liste');

            
            
        }
        return $this->render('editor/promotion/promo_attr.html.twig',[
            'form'=>$form->createView()
        ]);
    }


}
