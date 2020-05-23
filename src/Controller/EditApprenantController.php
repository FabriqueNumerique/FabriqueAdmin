<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Form\ApprenantType;
use App\Service\FileUploader;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditApprenantController extends AbstractController
{
    
    /**
     * afficher les apprenants 
     * 
     * @Route("/editor/apprenant/{page<\d+>?1}", name="editor_apprenant_liste")
     */
    public function apprenant_liste(ApprenantRepository $repo, $page)
    {
        
        $limit = 5;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);
        
        return $this->render('editor/apprenant/apprenant_liste.html.twig', [
            'apprenants' => $repo->findBy([], ['Nom' => 'asc'], $limit, $start),
            'pages' => $pages,
            'page' =>$page
        ]);
    }

    /**
     * ajouter un nouveau apprenant et créer un nouveau utilisateur dans User class
     * 
     * @Route("/editor/apprenant/new", name="editor_apprenant_new")
     */
    public function apprenant_new(Request $request, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader)
    {
        $newApprenant = new Apprenant();
        $Manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ApprenantType::class, $newApprenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // ici on récupére les réseaux qu'on a dans le formulaire de l'apprenant et on les persiste dans reseau
            // sans ce code, on arrivait à ajouter les réseaux dans reseau mais le id de l'apprenant était toujours vide
            // mail il falait avant tout permettre de persister et de 
            // remove dans l'annotation du reseaux dans l'entité apprenant

            foreach ($newApprenant->getReseaux() as $reseau) {
                $reseau->setApprenant($newApprenant);
                $Manager->persist($reseau);
            }

            // ici on récupère le fichier téléversé.et on teste s'il existe ou non.
            // s'il existe, on appelle le service FileUploader et on stock le fichier 
            // dans une variable pour le stocker dans l'apprenent en question
            $brochureFile = $form['brochure']->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $newApprenant->setAvatar($brochureFileName);
            }
            $mdp = strtolower($newApprenant->getNom() . $newApprenant->getPrenom());

            $mdp_hash = $encoder->encodePassword($newApprenant, $mdp);

            $newApprenant->setPassword($mdp_hash);

            try {
                $Manager->persist($newApprenant);
                $Manager->flush();

                $this->addFlash('success', "L'apprenant {$newApprenant->getFullname()} a été ajouté!");
                return $this->redirectToRoute('editor_apprenant_liste');
            } catch (Exception $e) {
                $this->addFlash('danger', 'Cet email existe déjà!');
            }
        }

        return $this->render('editor/apprenant/appreant_new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * afficher un apprenant
     * 
     * @Route("/editor/apprenant/show/{id}", name="editor_apprenant_show")
     */
    public function apprenant_show(Apprenant $apprenant)
    {
        return $this->render('editor/apprenant/apprenant_show.html.twig', [
            'apprenant' => $apprenant
        ]);
    }


    /**
     * modifier un apprenant
     * 
     * @Route("/editor/apprenant/edit/{id}", name="editor_edit_apprenant")
     */
    public function edit_apprenant(Apprenant $newApprenant, EntityManagerInterface $manager, Request $request, FileUploader $fileUploader)
    {

        $form = $this->createForm(ApprenantType::class, $newApprenant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // ici on récupére les réseaux qu'on a dans le formulaire de l'apprenant et on les persiste dans reseau
            // sans ce code, on arrivait à ajouter les réseaux dans reseau mais le id de l'apprenant était toujours vide
            // mail il falait avant tout permettre de persister et de 
            // remove dans l'annotation du reseaux dans l'entité apprenant
            foreach ($newApprenant->getReseaux() as $reseau) {
                $reseau->setApprenant($newApprenant);
                $manager->persist($reseau);
            }
            // dd($newApprenant);

            $brochureFile = $form['brochure']->getData();
            // dd($brochureFile);
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $newApprenant->setAvatar($brochureFileName);
            }

            $manager->flush();

            $this->addFlash("warning", "L'apprenant {$newApprenant->getFullname()} a été modifié!");
            return $this->redirectToRoute('editor_apprenant_liste');
        }
        return $this->render('editor/apprenant/apprenant_edit.html.twig', [
            'form' => $form->createView(),
            'apprenant' => $newApprenant
        ]);
    }


    /**
     * @Route("/editor/apprenant/delete/{id}", name="editor_apprenant_delete")
     */
    public function delete_user(Apprenant $apprenant, EntityManagerInterface $manager)
    {
        $manager->remove($apprenant);
        $manager->flush();
        $this->addFlash("danger", "L'apprenant {$apprenant->getFullname()} a été supprimée!");

        return $this->redirectToRoute('editor_apprenant_liste');
    }


    
    
    /**
     * gestion de competence
     * 
     * @Route("/editor/competence", name="editor_competence")
     */
    public function competence()
    {

        return $this->render('editor/apprenant/competence.html.twig', [
           
        ]);
    }



}
