<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    public function itemList(): Response {
        // Get categories

        // Setup menu items
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
                "children" => [
                    [
                        "title" => "kategorie 1",
                        "link" => "/"
                    ], 
                    [
                        "title" => "kategorie 2",
                        "link" => "/"
                    ],
                    [
                        "title" => "kategorie 3",
                        "link" => "/"
                    ],
                ]

            ]
        ];

        return $this->render('menu/itemsList.html.twig', [
            "menuItems" => $menuItems
        ]);

    }
}