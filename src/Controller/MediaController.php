<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Media;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class MediaController extends Controller
{
    /**
     * @Route("/admin/media/{id}", name="media", requirements={"id":"\d+"})
     */
    public function listMedia($id, EntityManagerInterface $em) {
        $medias =$em->getRepository(Media::class)->findBy(['genre'=>$id]);

        return $this->render('media/list.html.twig', [
            'medias' => $medias
        ]);
    }

    /**
     * @Route("/media/create", name="media_create")
     */
    public function create(Request $request, EntityManagerInterface $em) {
        $media = new Media();
        $mediaForm = $this->createForm(MediaType::class, $media);
        $mediaForm->handleRequest($request);
        $utilisateur = $this->getUser();
        if ($mediaForm->isSubmitted() && $mediaForm->isValid()) {

            // $file stores the uploaded PDF file
            $file = $mediaForm->get('picture')->getData();
            $media_content = $mediaForm->get('media')->getData();
            $extension = $media_content->getClientOriginalExtension();

            $uniqName = md5(uniqid());
            $fileName = $uniqName . '_picture' . '.' . $file->getClientOriginalExtension();
            $media_contentName = $uniqName . '_media' . '.' . $extension;
            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('file_directory'),
                    $fileName
                );
                $media_content->move(
                    $this->getParameter('file_directory'),
                    $media_contentName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $media->setPicture($fileName);
            $media->setExtension($extension);
            $media->setUtilisateurs($utilisateur);


            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media successfully added!');

            $genreofMedia = new Genre();
            $genreofMedia = $media->getGenre();
            return $this->redirectToRoute('media', array('id' => $genreofMedia->getId()));
        }

        return $this->render('media/add.html.twig', [
            'MediaForm' => $mediaForm->createView()
        ]);
    }




        /**
     * @Route("/admin/media/update/{id}", name="media_update", requirements={"id":"\d+"})
     */
    public function update(Media $media, Request $request, EntityManagerInterface $em) {

        $mediaForm = $this->createForm(MediaType::class, $media, ['fields' => 'update']);
        $mediaForm->handleRequest($request);

        if($mediaForm->isSubmitted() && $mediaForm->isValid()) {
            //dump($mediaForm);
            $file = $mediaForm->get('picture')->getData();
            $media_content = $mediaForm->get('media')->getData();
            $extension = $media_content->getClientOriginalExtension();

            $uniqName = md5(uniqid());
            $fileName = $uniqName . '_picture' . '.' . $file->getClientOriginalExtension();
            $media_contentName = $uniqName . '_media' . '.' . $extension;
            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('file_directory'),
                    $fileName
                );
                $media_content->move(
                    $this->getParameter('file_directory'),
                    $media_contentName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $media->setPicture($fileName);
            $media->setExtension($extension);


            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media successfully update!');
            $genreofMedia = new Genre();
            $genreofMedia = $media->getGenre();
            return $this->redirectToRoute('media', array('id' => $genreofMedia->getId()));
        }

        return $this->render('media/update.html.twig', [
            'MediaForm' => $mediaForm->createView()
        ]);
    }

    /**
     * @Route("/admin/media/delete", name="media_delele_default", defaults={"id":0})
     * @Route("/admin/media/delete/{id}", name="media_delete", requirements={"id":"\d+"})
     */
    public function delete(EntityManagerInterface $em, Media $media) {
        $genreofMedia = new Genre();
        $genreofMedia = $media->getGenre();
        $fileName = $media->getPicture();
        $em->remove($media);
        $em->flush();
        $this->addFlash("success", "Media successfully deleted!");
        $fs = new Filesystem();
        $fs->remove($this->get('kernel')->getRootDir().'/../public/uploads/files/'.$fileName);
        return $this->redirectToRoute("media", array('id' => $genreofMedia->getId()));
    }
}
