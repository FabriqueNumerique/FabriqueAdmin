<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormationRepository;
use App\Repository\PromotionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{

    /**
     * @Route("/editor/dashbord", name="editor_dashbord")
     */
    public function dashbord(PromotionRepository $repoPro, FormationRepository $repoFor, ApprenantRepository $repoApp, UserRepository $repoUse)
    {
        $promotions = $repoPro->findAll();
        $countPro = count($promotions);

        $formations = $repoFor->findAll();
        $countFor = count($formations);

        $apprenants = $repoApp->findAll();
        $countApp = count($apprenants);

        $users = $repoUse->findByRole();
        $countUse = count($users);

        return $this->render('editor/dashbord.html.twig', [
            'countPro' => $countPro,
            'countFor' => $countFor,
            'countApp' => $countApp,
            'countUse' => $countUse,
            'promotions' => $promotions,
            'formations' => $formations,
            'apprenants' => $apprenants,
            'users' => $users
        ]);
    }

    
    /**
     * @Route("/admin/utilisateur", name="admin_utilisateur")
     */
    public function utilisateur(UserRepository $repo)
    {

        // $user = $repo->findAll();
        $user=$repo->findByRole();
        return $this->render('admin/utilisateur.html.twig',[
           'users'=>$user 
        ]);
    }


    /**
     * @Route("/admin/delete_user/{id}", name="admin_delete_user")
     */
    public function delete_user(User $user)
    {

        $role=$user->getRoles();
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();
        if ($role[0]=='ROLE_USER'){
            $this->addFlash('danger', 'Un apprenant a été supprimée!');
        }else{
            $this->addFlash('danger', 'Un utilisateur a été supprimée!');
        }
        return $this->redirectToRoute('admin_utilisateur');      
    }

    /**
     * @Route("/admin/modif_user/{id}", name="admin_modif_user")
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
     * @Route("/admin/chercher_user", name="admin_chercher_user")
     */
    public function chercher(Request $request, UserRepository $repo)
    {

        // récupérer l'input chercher
        $email = $request->get('chercher');
        $user=$repo->findAllByEmail($email. "%");


        return $this->render('admin/utilisateur.html.twig', [
            'users' => $user,
            'message' => 'Aucun utilisateur trouvé !!'
        ]);
    }
}
