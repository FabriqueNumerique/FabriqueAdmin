<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EditCompetenceController extends AbstractController
{
    /**
     * @Route("/edit/competence", name="edit_competence")
     */
    public function index()
    {
        return $this->render('edit_competence/index.html.twig', [
            'controller_name' => 'EditCompetenceController',
        ]);
    }
}
