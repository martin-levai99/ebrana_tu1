<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    #[Route("/", methods: ["GET"], name: "index")]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}