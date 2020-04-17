<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/reserve", name="reserve_")
     */
class ReserveController extends AbstractController
{
    /**
     * @Route("/promotion", name="promotion")
     */
    public function promotion()
    {
        return $this->render('reserve/promotion.html.twig');
    }
}
