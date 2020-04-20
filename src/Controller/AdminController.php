<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    // /**
    //  * @Route("/admin/dashbord", name="admin_dashbord")
    //  */
    // public function dashbord(UserRepository $repo)
    // {
    //     $user = $repo->findAll();
    //     return $this->render('admin/dashbord.html.twig', [
    //         'users' => $user
    //     ]);
    // }

    /**
     * @Route("/admin/utilisateur", name="admin_utilisateur")
     */
    public function utilisateur(UserRepository $repo)
    {

        $user = $repo->findAll();
        
        return $this->render('admin/utilisateur.html.twig',[
           'users'=>$user 
        ]);
    }


    /**
     * @Route("/admin/delete_user/{id}", name="admin_delete_user")
     */
    public function delete_user($id,UserRepository $repo)
    {
        $user=$repo->find($id);

        $manager = $this->getDoctrine()->getManager();

        $manager->remove($user);
        $manager->flush();

        $this->addFlash('danger', 'Un utilisateur a été supprimée!');
        return $this->redirectToRoute('admin_utilisateur');
        // return $this->json([
        //     'message' => 'Un utilisateur a été supprimé '
        //     ]);        
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
        // return $this->json([
        //     'users' => $user
        // ]);
    }
}
