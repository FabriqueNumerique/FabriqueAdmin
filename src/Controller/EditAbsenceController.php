<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Retard;
use App\Form\AbsenceEditType;
use App\Form\AbsenceType;
use App\Form\RetardEditType;
use App\Form\RetardType;
use App\Repository\AbsenceRepository;
use App\Repository\PromoAppreRepository;
use App\Repository\RetardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditAbsenceController extends AbstractController
{

    /**
     * gestion de retartd.. afficher tous les retards avec une pagination
     * 
     * @Route("/editor/retard{page<\d+>?1}", name="editor_retard")
     */
    public function retard(RetardRepository $repo, $page)
    {
        $retard=$repo->retardActuel();
        $limit = 10;
        $start = $page * $limit - $limit;
        $all = count($retard);
        $pages = ceil($all / $limit);
        return $this->render('editor/retard_absence/retard.html.twig', [
            // une méthode qui affiche une liste des retards pour les promotions en cours
            'retards' => $repo->findBy(['id'=>$retard], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * ajouter un retard
     * 
     * @Route("/editor/retard/new", name="editor_retard_new")
     */
    public function retard_new(Request $request, EntityManagerInterface $manager)
    {
        $retard = new Retard();
        $form = $this->createForm(RetardType::class, $retard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($retard);
            $manager->flush();
                $this->addFlash("success", "Un retard concernant l'apprenant 
                                {$retard->getPromoAppre()->getApprenant()->getFullname()} 
                                et daté le {$retard->getDate()->format('d/m/Y')} a été ajouté!");

                return $this->redirectToRoute('editor_retard');
        }
       
        return $this->render('editor/retard_absence/retard_new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * modifier un retard
     * 
     * @Route("/editor/retard/edit/{id}", name="editor_retard_edit")
     */
    public function retard_edit(Request $request, EntityManagerInterface $manager, Retard $retard)
    {

        $form = $this->createForm(RetardEditType::class, $retard);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($retard);
            $manager->flush();
            $this->addFlash("warning", "Le retard concernant l'apprenant 
                            {$retard->getPromoAppre()->getApprenant()->getFullname()} 
                            et daté le {$retard->getDate()->format('d/m/Y')} a été modifié!");
                            
            return $this->redirectToRoute('editor_retard');
        }

        return $this->render('editor/retard_absence/retard_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * supprimer un retard
     * 
     * @Route("/editor/retard/delete/{id}", name="editor_retard_delete")
     */
    public function retard_delete(EntityManagerInterface $manager, Retard $retard)
    {
        $manager->remove($retard);
        $manager->flush();
        $this->addFlash("danger", "Le retard concernant l'apprenant {$retard->getPromoAppre()->getApprenant()->getFullname()} et daté le {$retard->getDate()->format('d/m/Y')} a été supprimée!");

        return $this->redirectToRoute('editor_retard');
    }

    /**
     * chercher un apprenant par son nom ou prénom
     * @Route("/editor/retard/chercher/{page<\d+>?1}", name="editor_retard_chercher")
     */
    public function chercher_retard(Request $request, RetardRepository $repo, $page)
    {

        // récupérer l'input chercher
        $nom = $request->get('chercher_retard');
        if ($nom) {

            // appeler la méthode findByNom() dansretardRepository
            $retard = $repo->findAllByName($nom . "%");

            $limit = 10;
            $start = $page * $limit - $limit;
            $all = count($retard);
            $pages = ceil($all / $limit);

            return $this->render('editor/retard_absence/retard.html.twig', [
                'retards' => $retard,
                'pages' => $pages,
                'page' => $page
            ]);
        } else {
            return $this->redirectToRoute('editor_retard');
        }
    }


    /**
     * gestion d'absence
     * 
     * @Route("/editor/absence{page<\d+>?1}", name="editor_absence")
     */
    public function absence(AbsenceRepository $repo, $page)
    {

        $absence = $repo->absenceActuel();
        $limit = 10;
        $start = $page * $limit - $limit;
        $all = count($absence);
        $pages = ceil($all / $limit);
        return $this->render('editor/retard_absence/absence.html.twig', [
            // une méthode qui affiche une liste des retards pour les promotions en cours
            'absences' => $repo->findBy(['id' => $absence], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);

    }

    /**
     * ajouter un absence
     * 
     * @Route("/editor/absence/new", name="editor_absence_new")
     */
    public function absence_new(Request $request, EntityManagerInterface $manager)
    {
        $absence = new Absence();
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $manager->persist($absence);
            $manager->flush();
            $this->addFlash("success", "Un absence daté le {$absence->getDateDebut()->format('d/m/Y')} 
                            concernant l'apprenant {$absence->getPromoAppre()->getApprenant()->getFullname()} 
                            a été ajouté!");

            return $this->redirectToRoute('editor_absence');
        }
        
        return $this->render('editor/retard_absence/absence_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * modifier un absence
     * 
     * @Route("/editor/absence/edit/{id}", name="editor_absence_edit")
     */
    public function absence_edit(Request $request, EntityManagerInterface $manager, Absence $absence)
    {

        $form = $this->createForm(AbsenceEditType::class, $absence);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($absence);
            $manager->flush();
            $this->addFlash("warning", "L'absence concernant l'apprenant 
                            {$absence->getPromoAppre()->getApprenant()->getFullname()}
                            et daté le {$absence->getDateDebut()->format('d/m/Y')} a été modifié!");

            return $this->redirectToRoute('editor_absence');
        }

        return $this->render('editor/retard_absence/absence_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * supprimer un absence
     * 
     * @Route("/editor/absence/delete/{id}", name="editor_absence_delete")
     */
    public function absence_delete(EntityManagerInterface $manager, Absence $absence)
    {
        $manager->remove($absence);
        $manager->flush();
        $this->addFlash("danger", "L'absence concernant l'apprenant 
                        {$absence->getPromoAppre()->getApprenant()->getFullname()} 
                        et daté le {$absence->getDateDebut()->format('d/m/Y')} a été supprimée!");

        return $this->redirectToRoute('editor_absence');
    }

    /**
     * chercher un apprenant par son nom ou prénom
     * @Route("/editor/absence/chercher/{page<\d+>?1}", name="editor_absence_chercher")
     */
    public function chercher_absence(Request $request, AbsenceRepository $repo, $page)
    {

        // récupérer l'input chercher
        $nom = $request->get('chercher_absence');
        if ($nom) {

            // appeler la méthode findByName() dans absenceRepository
            $absence = $repo->findAllByName($nom . "%");

            $limit = 10;
            $start = $page * $limit - $limit;
            $all = count($absence);
            $pages = ceil($all / $limit);

            return $this->render('editor/retard_absence/absence.html.twig', [
                'absences' => $absence,
                'pages' => $pages,
                'page' => $page
            ]);
        } else {
            return $this->redirectToRoute('editor_absence');
        }
    }

}
