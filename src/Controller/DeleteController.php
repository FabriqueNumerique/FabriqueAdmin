<?php

namespace App\Controller;

use App\Repository\ApprenantRepository;
use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
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
        return $this->redirectToRoute('editor_promotion');

        // return $this->json([
        //     'message' => 'Une promotion a été supprimée '
        // ]);
    }


    /**
     * supprimer une formation
     * 
     * @Route("/admin/delete_formation/{id}", name="editor_delete_formation")
     */
    public function delete_formation($id, FormationRepository $repo, PromotionRepository $repoPromotion)
    {
        $formation = $repoPromotion->findOneBy(['Formation'=>$id]);
        if ($formation){
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette formation qui est liée au moins à une promotion !');
            return $this->redirectToRoute('editor_formation');

        }else{

            $formation = $repo->find($id);
    
            $manager = $this->getDoctrine()->getManager();
    
            $manager->remove($formation);
            $manager->flush();
    
            $this->addFlash('danger', 'Une formation a été supprimée!');
            return $this->redirectToRoute('editor_formation');
        }

        // return $this->json([
        //     'message' => 'Une promotion a été supprimée '
        // ]);
    }

    /**
     * supprimer un apprenant
     * 
     * @Route("/admin/delete_apprenant/{id}", name="editor_delete_apprenant")
     */
    public function delete_apprenant($id, ApprenantRepository $repo)
    {
        $apprenant = $repo->find($id);

        $manager = $this->getDoctrine()->getManager();

        $manager->remove($apprenant);
        $manager->flush();

        $this->addFlash('danger', 'Un apprenant a été supprimé!');
        return $this->redirectToRoute('editor_apprenant');

        // return $this->json([
        //     'message' => 'Une promotion a été supprimée '
        // ]);
    }
}
