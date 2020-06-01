<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{



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
     * @Route("/editor/formation/delete/{id}", name="editor_delete_formation")
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


}
