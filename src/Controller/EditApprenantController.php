<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\PromoAppre;
use App\Form\ApprenantType;
use App\Service\FileUploader;
use App\Repository\ApprenantRepository;
use App\Repository\PromoAppreRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditApprenantController extends AbstractController
{
    
    /**
     * afficher les apprenants avec une pagination 
     * 
     * @Route("/editor/apprenant/{page<\d+>?1}", name="editor_apprenant_liste")
     */
    public function apprenant_liste(ApprenantRepository $repo, $page)
    {
        
        $limit = 10;
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
        $apprenant = new Apprenant();
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ApprenantType::class, $apprenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // récupérer les réseaux ajoutés et les insérer dans la table réseau
            foreach ($apprenant->getReseaux() as $reseau) {
                $reseau->setApprenant($apprenant);
                $manager->persist($reseau);
            }

            // ici on récupère le fichier téléversé.et on teste s'il existe ou non.
            // s'il existe, on appelle le service FileUploader et on stock le fichier 
            // dans une variable pour le stocker dans l'apprenent en question
            $brochureFile = $form['brochure']->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $apprenant->setAvatar($brochureFileName);
            }

            // créer un mot de passe comboné du nom et du prénom et le crypter
            $mdp = strtolower($apprenant->getNom() . $apprenant->getPrenom());

            $mdp_hash = $encoder->encodePassword($apprenant, $mdp);

            $apprenant->setPassword($mdp_hash);

            try {

                $manager->persist($apprenant);
                $manager->flush();

                $this->addFlash('success', "L'apprenant - {$apprenant->getFullname()} - a été ajouté!");
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
    public function apprenant_show(Apprenant $apprenant, PromoAppreRepository $repo)
    {
        return $this->render('editor/apprenant/apprenant_show.html.twig', [
            'all' =>$repo->findBy(['apprenant'=> $apprenant])
        ]);
    }


    /**
     * modifier un apprenant
     * 
     * @Route("/editor/apprenant/edit/{id}", name="editor_edit_apprenant")
     */
    public function edit_apprenant(Apprenant $newApprenant, Request $request, FileUploader $fileUploader)
    {

        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ApprenantType::class, $newApprenant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

           
            // récupérer les réseaux ajoutés et les insérer dans la table réseau
            foreach ($newApprenant->getReseaux() as $reseau) {
                $reseau->setApprenant($newApprenant);
                $manager->persist($reseau);
            }
           

            $brochureFile = $form['brochure']->getData();
            
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
     * supprimer un apprenant
     * @Route("/editor/apprenant/delete/{id}", name="editor_apprenant_delete")
     */
    public function delete_apprenant(Apprenant $apprenant, EntityManagerInterface $manager)
    {
        $manager->remove($apprenant);
        $manager->flush();
        $this->addFlash("danger", "L'apprenant {$apprenant->getFullname()} a été supprimée!");

        return $this->redirectToRoute('editor_apprenant_liste');
    }


    /**
     * chercher un apprenant par son nom ou prénom
     * @Route("/editor/apprenant/chercher/{page<\d+>?1}", name="editor_apprenant_chercher")
     */
    public function chercher(Request $request, ApprenantRepository $repo, $page)
    {

        // récupérer l'input chercher
        $nom = $request->get('chercher_apprenant');
        if ($nom){

            // appeler la méthode dindByNom() dans ApprenantRepository
            $apprenant = $repo->findAllByNom($nom . "%");
    
            $limit = 10;
            $start = $page * $limit - $limit;
            $all = count($apprenant);
            $pages = ceil($all / $limit);
    
            return $this->render('editor/apprenant/apprenant_liste.html.twig', [
                'apprenants' => $apprenant,
                'pages' => $pages,
                'page' => $page
            ]);
        }
        else{
            return $this->redirectToRoute('editor_apprenant_liste');
        }

    }
    
    /**
     * gestion de competence
     * 
     * @Route("/editor/competence", name="editor_competence")
     */
    public function competence(ApprenantRepository $repo)
    {

        return $this->render('editor/apprenant/competence.html.twig', [
           
        ]);
    }



}
