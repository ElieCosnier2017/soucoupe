<?php

namespace App\Controller;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends Controller
{
    /**
     * @Route("/media", name="media")
     */
    public function list(EntityManagerInterface $em) {
        $media =$em->getRepository(Media::class)->findAll();

        return $this->render('media/list.html.twig', [
            'typesMedia' => $media
        ]);
    }

    /**
     * @Route("/media/create", name="media_create")
     */
    public function create(Request $request, EntityManagerInterface $em) {
        $media = new Media();
        $mediaForm = $this->createForm(MediaType::class, $media);

        $mediaForm->handleRequest($request);
        if($mediaForm->isSubmitted() && $mediaForm->isValid()) {
            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media successfully added!');
            return $this->redirectToRoute('media');
        }

        return $this->render('media/add.html.twig', [
            'MediaForm' => $mediaForm->createView()
        ]);
    }

    /**
     * @Route("/media/update/{id}", name="media_update", requirements={"id":"\d+"})
     */
    public function update(Media $media, Request $request, EntityManagerInterface $em) {
        $mediaForm = $this->createForm(MediaType::class, $media);
        $mediaForm->handleRequest($request);

        if($mediaForm->isSubmitted() && $mediaForm->isValid()) {
            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media successfully update!');
            return $this->redirectToRoute('media');
        }

        return $this->render('media/add.html.twig', [
            'mediaForm' => $mediaForm->createView()
        ]);
    }

    /**
     * @Route("/media/delete", name="media_delele_default", defaults={"id":0})
     * @Route("/media/delete/{id}", name="media_delete", requirements={"id":"\d+"})
     */
    public function delete(EntityManagerInterface $em, Media $media) {
        if(count($media->getIdeas()) > 0){
            $this->addFlash('error', "You can't delete this media!!");
            return $this->redirectToRoute('media');
        }

        $em->remove($media);
        $em->flush();
        $this->addFlash("success", "Media successfully deleted!");
        return $this->redirectToRoute("media");
    }
}
