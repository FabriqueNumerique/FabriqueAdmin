<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ReserveController extends AbstractController
{
    /**
     * @Route("/reserve/promotion", name="reserve_promotion")
     */
    public function promotion()
    {
        
        return $this->render('reserve/promotion.html.twig');
    }
}
