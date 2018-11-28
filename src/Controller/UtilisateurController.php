<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UtilisateurController extends Controller
{
    /**
     * @Route("/test", name="home")
     */
    public function index(){
        return $this->render("main/index.html.twig");

    }

    /**
     * @Route("/listuser", name="list_user")
     */
    public function listuser(EntityManagerInterface $em){

        $allUsers = $em->getRepository(Utilisateur::class)->findAll();
        return $this->render("utilisateur/list.html.twig", ["allUsers" => $allUsers]);
    }

    /**
     * @Route("/edituser/{id}", name="edit_user", requirements={"id":"\d+"})
     */
    public function edituser($id, Request $request, EntityManagerInterface $em){

        $repo = $em->getRepository(Utilisateur::class);
        $user = $repo->find($id);
        $form  = $this->createForm(UtilisateurType::class,$user,['fields' => ['update']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form);
            if($form['roles']->getData() == null) {
                $user->setRoles(['ROLE_ADMIN']);
            } elseif($form['roles']->getData() == true) {
                $user->setRoles(['ROLE_USER']);
            }
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "This User account has been update!");
//            return $this->redirectToRoute("list_user");
        }
        return $this->render("utilisateur/update.html.twig", ["form" => $form->createView(),"user" => $user]);
    }


    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Your account has been created!");
            return $this->redirectToRoute("login");
        }

        return $this->render("utilisateur/register.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('utilisateur/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}
}
