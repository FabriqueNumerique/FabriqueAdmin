<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Cette route appelle la page d'accueil dans le fichier main/index.html.twig
     * @Route("/", name="main")
     */
    public function index()
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * une fois l'utilisateur est authentifié, il est dirigé vers cette route qui va tester son rôle
     * @Route("/check_role", name="check_role")
     */
    public function check_role()
    {
        
        if ($this->getUser()){

            if ($this->getUser()->getRoles()[0]=='ROLE_ADMIN'){
                return $this->redirectToRoute('editor_dashbord');
            }
            if ($this->getUser()->getRoles()[0] == 'ROLE_EDITOR') {
                return $this->redirectToRoute('editor_dashbord');
            }
            if ($this->getUser()->getRoles()[0]=='ROLE_RESERVE'){
                return $this->redirectToRoute('reserve_promotion');
            }
            if ($this->getUser()->getRoles()[0] == 'ROLE_USER') {
                return $this->redirectToRoute('reserve_promotion');
            }
        
        }

        
    }

    /**
     * si l'utilisateur essaye d'aller vers une route interdite, il sera dirigé vers cette route où un message d'erreur s'affichera
     * @Route("/access_denied", name="access_denied")
     */
    public function access_denied()
    {
        return $this->render('main/access_denied.html.twig');
    }
}
