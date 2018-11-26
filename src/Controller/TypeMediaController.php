<?php

namespace App\Controller;

use App\Entity\TypeMedia;
use App\Form\TypeMediaType;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Node\Expression\Binary\EndsWithBinary;

class TypeMediaController extends Controller
{
    /**
     * @Route("/type-media", name="type_media")
     */
    public function list(EntityManagerInterface $em) {
        $typesMedia =$em->getRepository(TypeMedia::class)->findAll();

        return $this->render('type_media/list.html.twig', [
            'typesMedia' => $typesMedia
        ]);
    }

    /**
     * @Route("/type-media/create", name="type_media_create")
     */
    public function create(Request $request, EntityManagerInterface $em) {
        $typeMedia = new TypeMedia();
        $typeMediaForm = $this->createForm(TypeMediaType::class, $typeMedia);

        $typeMediaForm->handleRequest($request);
        if($typeMediaForm->isSubmitted() && $typeMediaForm->isValid()) {
            $em->persist($typeMedia);
            $em->flush();

            $this->addFlash('success', 'Type Media successfully added!');
            return $this->redirectToRoute('type_media');
        }

        return $this->render('type_media/add.html.twig', [
            'categoryForm' => $typeMediaForm->createView()
        ]);
    }

    /**
     * @Route("/type-media/update/{id}", name="type_media_update", requirements={"id":"\d+"})
     */
    public function update(TypeMedia $typeMedia, Request $request, EntityManagerInterface $em) {
        $typeMediaForm = $this->createForm(TypeMediaType::class, $typeMedia);
        $typeMediaForm->handleRequest($request);

        if($typeMediaForm->isSubmitted() && $typeMediaForm->isValid()) {
            $em->persist($typeMedia);
            $em->flush();

            $this->addFlash('success', 'Type Media successfully update!');
            return $this->redirectToRoute('type_media');
        }

        return $this->render('type_media/add.html.twig', [
            'categoryForm' => $typeMediaForm->createView()
        ]);
    }

    /**
     * @Route("/type_media/delete", name="type_media_delele_default", defaults={"id":0})
     * @Route("/type_media/delete/{id}", name="type_media_delete", requirements={"id":"\d+"})
     */
    public function delete(EntityManagerInterface $em, TypeMedia $typeMedia) {
        if(count($typeMedia->getIdeas()) > 0){
            $this->addFlash('error', "You can't delete this type media!!");
            return $this->redirectToRoute('type_list');
        }

        $em->remove($typeMedia);
        $em->flush();
        $this->addFlash("success", "Type Media successfully deleted!");
        return $this->redirectToRoute("type_list");
    }
}
