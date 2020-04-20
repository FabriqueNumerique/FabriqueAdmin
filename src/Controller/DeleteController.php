<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    /**
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
     * @Route("/admin/delete_formation/{id}", name="editor_delete_formation")
     */
    public function delete_formation($id, FormationRepository $repo)
    {
        $formation = $repo->find($id);

        $manager = $this->getDoctrine()->getManager();

        $manager->remove($formation);
        $manager->flush();

        $this->addFlash('danger', 'Une formation a été supprimée!');
        return $this->redirectToRoute('editor_formation');

        // return $this->json([
        //     'message' => 'Une promotion a été supprimée '
        // ]);
    }
}
