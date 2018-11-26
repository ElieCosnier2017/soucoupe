<?php

namespace App\Controller;

use App\Entity\TypeMedia;
use App\Form\TypeMediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TypeMediaController extends Controller
{
    /**
     * @Route("/type/media", name="type_media")
     */
    public function index()
    {
        return $this->render('type_media/index.html.twig', [
            'controller_name' => 'TypeMediaController',
        ]);
    }

    public function list(EntityManagerInterface $em) {
        $typesMedia =$em->getRepository(TypeMedia::class)->findAll();

        return $this->render('type_media/list.html.twig', [
            'typesMedia' => $typesMedia
        ]);
    }

    public function create() {
        $typeMedia = new TypeMedia();
        $typeMediaForm = $this->createForm(TypeMediaType::class, $typeMedia);

    }



    public function update() {

    }

    public function delete() {

    }
}
