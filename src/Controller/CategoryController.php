<?php 

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends AbstractController {
        
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route("/kategorie/nova-kategorie", methods: ["GET", "POST"], name: "category_create")]
    public function create(Request $request): Response {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $newCategory = $form->getData();

            $this->em->persist($newCategory);
            $this->em->flush();

            return $this->redirectToRoute("post_index");
        }

        return $this->render('category/create.html.twig', [
            "form" => $form->createView()
        ]);
    }


    #[Route("/kategorie/upravit-kategorii/{id}", methods: ["GET", "POST"], name: "category_edit")]
    public function edit($id, Request $request): Response {
        $category = $this->em->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $newCategory = $form->getData();

            $this->em->persist($newCategory);
            $this->em->flush();

            return $this->redirectToRoute("category_single", ["id" => $id]);
        }

        return $this->render('category/edit.html.twig', [
            "cat" =>  $category,
            "form" => $form->createView()
        ]);
    }


    #[Route("/kategorie/smazat-kategorii/{id}", methods: ["GET", "DELETE"], name: "category_delete")]
    public function delete($id): Response {
        $category = $this->em->getRepository(Category::class)->find($id);
        $this->em->remove($category);
        $this->em->flush();

        return $this->redirectToRoute("post_index");
    }


    #[Route("/kategorie/{id}", methods: ["GET"], name: "category_single")]
    public function single($id): Response {
        $category = $this->em->getRepository(Category::class)->find($id);
        
        return $this->render('category/single.html.twig', [
            "cat" => $category
        ]);
    }
}