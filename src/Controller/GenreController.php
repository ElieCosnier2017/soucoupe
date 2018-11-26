<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends Controller
{
    /**
     * @Route("/admin/genre", name="genre")
     */
    public function index()
    {
        return $this->render('genre/index.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }

    /**
     * @Route("/admin/listgenre", name="listgenre")
     */
    public function list(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Genre::class);
        $genres = $repo->findAll();
        return $this->render('genre/list.html.twig', ["genres" => $genres]);
    }

    /**
     * @Route("/admin/deleteGenre/{id}")
     */
    public function delete(Genre $genre ,EntityManagerInterface $em){

        $em->remove($genre);
        $em->flush();

        return new Response("Delete genre");
    }

    /**
     * @Route("/admin/createGebre", name="create")
     */
    public function create(EntityManagerInterface $em, Request $request){
        $genre = new Genre();
        $GenreForm = $this->createForm(GenreType::class, $genre);
        $GenreForm->handleRequest($request);

        if($GenreForm->isSubmitted() && $GenreForm->isValid()){
            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', "la  genre a été ajoutée !");

            return $this->redirectToRoute('listcat');

        }
        return $this->render('genre/create.html.twig',
            ['genreForm' => $GenreForm->createView()]
        );
    }

    /**
     * @Route("/admin/updateGenre/{id}", name="update", requirements={"id"="\d+"})
     */
    public function update($id, EntityManagerInterface $em, Request $request){
        //$repo = $this->getDoctrine()->getRepository(Serie::class);
        $repo = $em->getRepository(Genre::class);

        $genre = $repo->find($id);

        $genreForm = $this->createForm(GenreType::class, $genre)
        ->handleRequest($request);

        if($genreForm->isSubmitted() && $genreForm->isValid()){
            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', "le genre a été mise à jour !");

            return $this->redirectToRoute('list');

        }
        return $this->render('genre/update.html.twig',
            ['genreForm' => $genreForm->createView()]
        );
    }


}
