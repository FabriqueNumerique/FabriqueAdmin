<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Repository\PromoAppreRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ReserveController extends AbstractController
{


    /**
     * Afficher toutes les promotions avec la possibilité de modifier et de supprimer
     * le parametre 'page': le \d+ = nombre, ? = optionnel, 1 = valeur par defaut
     * 
     * @Route("/reserve/promotion{page<\d+>?1}", name="reserve_promotion")
     */
    public function promo_list(PromotionRepository $repo, $page)
    {
        $limit = 10;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);

        return $this->render('reserve/promotion.html.twig', [
            'promotions' => $repo->findBy([], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Afficher une promotion avec ses apprenants
     * 
     * @Route("/reserve/promotion/show/{id}", name="reserve_show_promotion")
     */
    public function show_promotion(PromoAppreRepository $repo, Promotion $promotion)
    {

        return $this->render('reserve/promotion_show.html.twig', [
            'promotion' => $promotion,
            'apprenants' => $repo->findBy(['promotion' => $promotion])
        ]);
    }


    /**
     * afficher un apprenant
     * 
     * @Route("/reserve/apprenant_show/{id}", name="reserve_apprenant_show")
     */
    public function pro_apprenant_show(Apprenant $apprenant, PromoAppreRepository $repo)
    {
        return $this->render('reserve//apprenant_show.html.twig', [
            'apprenant' => $apprenant,
            'promotions' => $repo->findBy(['apprenant' => $apprenant])
        ]);
    }

}
