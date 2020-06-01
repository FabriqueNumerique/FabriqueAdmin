<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ApprenantRepository;
use App\Repository\ContactsRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\FormationRepository;
use App\Repository\OffresRepository;
use App\Repository\PromotionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{

    /**
     * 
     * @Route("/editor/dashbord", name="editor_dashbord")
     */
    public function dashbord(
                            PromotionRepository $repoPro,
                            FormationRepository $repoFor,
                            ApprenantRepository $repoApp,
                            UserRepository $repoUse,
                            EntrepriseRepository $repoEntr,
                            ContactsRepository $repoContact,
                            OffresRepository $repoOffre
                        )
    {
        // count promotions
        $promotions = $repoPro->findAll();
        $countPro = count($promotions);

        // count formations
        $formations = $repoFor->findAll();
        $countFor = count($formations);

        // count learners
        $apprenants = $repoApp->findAll();
        $countApp = count($apprenants);

        // count users
        $users = $repoUse->findByRole();
        $countUse = count($users);

        // count companies
        $entreprises = $repoEntr->findAll();
        $countEntreprise = count($entreprises);

        // count contacts
        $contacts = $repoContact->findAll();
        $countContact = count($contacts);

        // count offres
        $offres = $repoOffre->findAll();
        $countoffre = count($offres);

        return $this->render('editor/dashbord.html.twig', [
            'countPromotion'    => $countPro,
            'countFormation'    => $countFor,
            'countApprenant'    => $countApp,
            'countUser'         => $countUse,
            'countEntreprise'   => $countEntreprise,
            'countContact'      => $countContact,
            'countOffres'       => $countoffre,
            'promotions'        => $promotions,
            'formations'        => $formations,
            'apprenants'        => $apprenants,
            'users'             => $users,
            'entreprises'       => $entreprises,
            'contacts'          => $contacts,
            'offres'            => $offres
        ]);
    }

    
    /**
     * @Route("/admin/utilisateur", name="admin_utilisateur")
     */
    public function utilisateur(UserRepository $repo)
    {

        // afficher les utilisateurs qui n'ont pas le status apprenants
        $user=$repo->findByRole();
        return $this->render('admin/utilisateur.html.twig',[
           'users'=>$user 
        ]);
    }


    /**
     * delete user
     * @Route("/admin/utilisateur/delete/{id}", name="admin_delete_user")
     */
    public function delete_user(User $user)
    {

        $role=$user->getRoles();
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();
        
       
        $this->addFlash('danger', "L'utilisateur {$user->getEmail()} a été supprimée!");
       
        return $this->redirectToRoute('admin_utilisateur');      
    }


    /**
     * éditer un utilisateur
     * @Route("/admin/utilisateur/modif/{id}", name="admin_modif_user")
     */
    public function modif_user($id, UserRepository $repo)
    {
        $user = $repo->find($id);
        dd($user);
        $manager = $this->getDoctrine()->getManager();

        $manager->flush();

        return $this->json([
            'message' => 'Un utilisateur a été supprimé '
        ]);

    }

    /**
     * chercher un utilisateur par son email
     * @Route("/admin/utilisateur/chercher", name="admin_chercher_user")
     */
    public function chercher(Request $request, UserRepository $repo)
    {

        // récupérer l'input chercher
        $email = $request->get('chercher');

        // appeler la méthode dindByEmail() dans UserRepository
        $user=$repo->findAllByEmail($email. "%");

        return $this->render('admin/utilisateur.html.twig', [
            'users' => $user,
            'message' => 'Aucun utilisateur trouvé !!'
        ]);
    }
}
