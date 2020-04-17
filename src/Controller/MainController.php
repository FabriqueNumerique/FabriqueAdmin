<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/back", name="back")
     */
    public function start()
    {
        // dd($this->getUser()->getRoles()[0]);
        if ($this->getUser()->getRoles()[0]=='ROLE_ADMIN'){
            return $this->redirectToRoute('admin_dashbord');
        }
        if ($this->getUser()->getRoles()[0]=='ROLE_RESERVE'){
            return $this->redirectToRoute('reserve_promotion');
        }
        return $this->render('base.html.twig');
    }
}
