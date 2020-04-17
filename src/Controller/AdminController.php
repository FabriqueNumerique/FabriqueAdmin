<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashbord", name="dashbord")
     */
    public function dashbord(UserRepository $repo)
    {
        $user = $repo->findAll();
        return $this->render('admin/dashbord.html.twig', [
            'users' => $user
        ]);
    }

    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function utilisateur(UserRepository $repo)
    {

        $user = $repo->findAll();
        return $this->render('admin/utilisateur.html.twig',[
           'users'=>$user 
        ]);
    }

    /**
     * @Route("/promotion", name="promotion")
     */
    public function promotion()
    {
        return $this->render('admin/promotion.html.twig');
    }
}
