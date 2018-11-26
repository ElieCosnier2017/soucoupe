<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends Controller
{
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function index()
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("/listusers", name="listusers")
     */
    public function list(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Utilisateur::class);
        $users = $repo->findAll();
        return $this->render('utilisateur/list.html.twig', ["genres" => $users]);
    }

    /**
     * @Route("//deleteuser/{id}")
     */
    public function delete(Utilisateur $utilisateur ,EntityManagerInterface $em){

        $em->remove($utilisateur);
        $em->flush();

        return new Response("Delete user");
    }

    /**
     * @Route("/createuser", name="createuser")
     */
    public function create(EntityManagerInterface $em, Request $request){
        $user = new Utilisateur();
        $UserForm = $this->createForm(UtilisateurType::class, $user);
        $UserForm->handleRequest($request);

        if($UserForm->isSubmitted() && $UserForm->isValid()){
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "l'user a été ajouté !");

            return $this->redirectToRoute('listuser');

        }
        return $this->render('utilisateur/create.html.twig',
            ['$UserForm' => $UserForm->createView()]
        );
    }

    /**
     * @Route("/updateuser/{id}", name="updateuser", requirements={"id"="\d+"})
     */
    public function update($id, EntityManagerInterface $em, Request $request){
        //$repo = $this->getDoctrine()->getRepository(Serie::class);
        $repo = $em->getRepository(Utilisateur::class);

        $user = $repo->find($id);

        $userForm = $this->createForm(UtilisateurType::class, $user)
            ->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()){
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "l'utilisateur a été mise à jour !");

            return $this->redirectToRoute('list');

        }
        return $this->render('utilisateur/update.html.twig',
            ['userForm' => $userForm->createView()]
        );
    }
}
