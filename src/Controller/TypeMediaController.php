<?php

namespace App\Controller;

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
}
