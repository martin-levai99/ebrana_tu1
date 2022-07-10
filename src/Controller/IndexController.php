<?php 

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class IndexController extends AbstractController {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route("/", methods: ["GET"], name: "index")]
    public function index(): Response {
        $categories = $this->em->getRepository(Category::class)->findAll();

        return $this->render('index.html.twig', [
            "categories" => $categories
        ]);
    }
}