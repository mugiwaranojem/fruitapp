<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->render('index.html.twig', ['page_name' => 'Fruit Lists']);
    }

    /**
     * @Route("/favorites", name="favorites")
     */
    public function displayFavorites()
    {
        return $this->render('favorites.html.twig', ['page_name' => 'Favorite Fruits']);
    }
}
