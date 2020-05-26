<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditEntrepriseController extends AbstractController
{
    /**
     * @Route("/editor/entreprise", name="editor_entreprise")
     */
    public function entreprise_list(EntrepriseRepository $repo)
    {
        return $this->render('editor/entreprise/entreprise_list.html.twig', [
            'entreprises'=>$repo->findAll()
        ]);
    }

    /**
     * @Route("/editor/entreprise/new", name="editor_entreprise_new")
     */
    public function entreprise_new(EntityManagerInterface $manager, Request $request)
    {
        $entreprise=new Entreprise();
        $form=$this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            foreach ($entreprise->getContact() as $contact) {
                $contact->setEntreprise($entreprise);
                $manager->persist($contact);
            }

            foreach ($entreprise->getOffres() as $offre) {
                $offre->setEntreprise($entreprise);
                $manager->persist($offre);
            }

            $manager->persist($entreprise);
            $manager->flush();
            $this->addFlash("success", "L'entreprise - {$entreprise->getNom()} - a été ajoutée!");
            return $this->redirectToRoute('editor_entreprise');
        }
        
        return $this->render('editor/entreprise/entreprise_new.html.twig', [
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/editor/entreprise/edit/{id}", name="editor_entreprise_edit")
     */
    public function entreprise_edit(EntityManagerInterface $manager,Request $request, Entreprise $entreprise)
    {
        $form = $this->createForm(EntrepriseType::class,$entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($entreprise->getContact() as $contact) {
                $contact->setEntreprise($entreprise);
                $manager->persist($contact);
            }

            foreach ($entreprise->getOffres() as $offre) {
                $offre->setEntreprise($entreprise);
                $manager->persist($offre);
            }

            $manager->persist($entreprise);
            $manager->flush();
            $this->addFlash("warning", "L'entreprise - {$entreprise->getNom()} - a été modifiée!");
            return $this->redirectToRoute('editor_entreprise');
        }
        return $this->render('editor/entreprise/entreprise_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
}
