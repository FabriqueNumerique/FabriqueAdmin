<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Entity\Entreprise;
use App\Entity\Offres;
use App\Form\ContactType;
use App\Form\EntrepriseType;
use App\Form\OffreType;
use App\Repository\ContactsRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\OffresRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditEntrepriseController extends AbstractController
{
    /**
     * @Route("/editor/entreprise{page<\d+>?1}", name="editor_entreprise")
     */
    public function entreprise_list(EntrepriseRepository $repo, $page)
    {
        $limit = 10;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);
        return $this->render('editor/entreprise/entreprise_list.html.twig', [
            'entreprises' => $repo->findBy([], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
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

            $manager->persist($entreprise);
            $manager->flush();
            $this->addFlash("warning", "L'entreprise - {$entreprise->getNom()} - a été modifiée!");
            return $this->redirectToRoute('editor_entreprise');
            

        }
        return $this->render('editor/entreprise/entreprise_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/editor/entreprise/delete/{id}", name="editor_entreprise_delete")
     */
    public function entreprise_delete(EntityManagerInterface $manager, Entreprise $entreprise)
    {
        $manager->remove($entreprise);
        $manager->flush();
        $this->addFlash("danger", "L'entreprise intiltulée - {$entreprise->getNom()} - a été supprimée!");
        return $this->redirectToRoute('editor_entreprise');
    }

    /**
     * @Route("/editor/offre{page<\d+>?1}", name="editor_offre")
     */
    public function offre_list(OffresRepository $repo, $page)
    {
        $limit = 10;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);

        return $this->render('editor/entreprise/offre_list.html.twig', [
            'offres' => $repo->findBy([], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * @Route("/editor/offre/new", name="editor_offre_new")
     */
    public function offre_new(EntityManagerInterface $manager, Request $request, FileUploader $fileUploader)
    {
        $offre = new Offres();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form['brochure']->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $offre->setCahierDesCharges($brochureFileName);
            }

            $manager->persist($offre);
            $manager->flush();
            $this->addFlash("success", "Un offre de l'entreprise {$offre->getEntreprise()->getNom()} - a été ajouté !");
            return $this->redirectToRoute('editor_offre');
        }

        return $this->render('editor/entreprise/offre_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editor/offre/edit{id}", name="editor_offre_edit")
     */
    public function offre_edit(EntityManagerInterface $manager, Request $request, FileUploader $fileUploader,Offres $offre)
    {

        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form['brochure']->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $offre->setCahierDesCharges($brochureFileName);
            }

            $manager->persist($offre);
            $manager->flush();
            $this->addFlash("warning", "Un offre de l'entreprise {$offre->getEntreprise()->getNom()} - a été modifié !");
            return $this->redirectToRoute('editor_offre');
        }

        return $this->render('editor/entreprise/offre_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editor/offre/delete/{id}", name="editor_offre_delete")
     */
    public function offre_delete(EntityManagerInterface $manager, Offres $offre)
    {
        $manager->remove($offre);
        $manager->flush();
        $this->addFlash("danger", "L'offre de l'entreprise - {$offre->getEntreprise()->getNom()} - a été supprimée!");
        return $this->redirectToRoute('editor_offre');
    }

    /**
     * @Route("/editor/contact{page<\d+>?1}", name="editor_contact")
     */
    public function contact_list(ContactsRepository $repo, $page)
    {
        $limit = 10;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);

        return $this->render('editor/entreprise/contact_list.html.twig', [
            'contacts' => $repo->findBy([], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * @Route("/editor/contact/delete/{id}", name="editor_contact_delete")
     */
    public function contact_delete(EntityManagerInterface $manager, Contacts $contact)
    {
        $manager->remove($contact);
        $manager->flush();
        $this->addFlash("danger", "Le contact - {$contact->getFullName()} - a été supprimé 
                        de l'entreprise - {$contact->getEntreprise()->getNom()} -!");
        return $this->redirectToRoute('editor_contact');
    }


    /**
     * modifier un contact
     * 
     * @Route("/editor/contact/edit/{id}", name="editor_edit_contact")
     */
    public function edit_contact(Contacts $contact, Request $request)
    {

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Manager = $this->getDoctrine()->getManager();

            $Manager->flush();

            $this->addFlash('warning', "Le contact - {$contact->getFullName()} - a été modifiée!");
            return $this->redirectToRoute('editor_contact');
        }
        return $this->render('editor/entreprise/contact_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
