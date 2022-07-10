<?php 

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }


    public function itemList(): Response {

        // Get categories
        $categories = $this->em->getRepository(Category::class)->findAll();

        // Set up category links
        $category_links = [];
        foreach ($categories as $cat) {
            $category_links[] = [
                "title" => $cat->getTitle(),
                "link" => "/kategorie/" . $cat->getId()
            ];
        }

        // Set up menu items
        $menuItems = [
            [
                "title" => "Domů",
                "link" =>  "/"
            ],
            [
                "title" => "Magazín",
                "link" => "/blog"
            ],
            [
                "title" => "Kategorie",
                "link" => "#",
                "children" => $category_links
            ]
        ];

        return $this->render('menu/itemList.html.twig', [
            "menuItems" => $menuItems
        ]);

    }
}