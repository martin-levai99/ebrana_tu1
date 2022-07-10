<?php 

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class PostController extends AbstractController {
        
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }


    #[Route("/blog/", methods: ["GET"], name: "post_index")]
    public function index(): Response {

        $repository = $this->em->getRepository(Post::class);

        // Select * fron posts
        // $posts = $repository->findAll();

        // Select * fron posts where ID = 5
        // $posts = $repository->find(5);

        // Select * fron posts order by ID DESC
        // $posts = $repository->findBy([],["id" => "DESC"]);

        // Select * fron posts where ID = 7 AND title = "Článek 1"
        // $posts = $repository->findOneBy(["id" => 7, "title" => "Článek 1"]);


        // Select count() from posts
        // $posts = $repository->count([]);

        // Select count() from posts where id = 7
        // $posts = $repository->count(["id" => 7]);

        // Classname
        // $posts = $repository->getClassname();

        $posts = $repository->findAll();
        // dd($posts);

        return $this->render('post/index.html.twig', [
            "posts" => $repository->findAll()
        ]);
    }


    #[Route("/blog/novy-clanek", methods: ["GET", "POST"], name: "post_create")]
    public function create(Request $request): Response {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $newPost = $form->getData();

            $thumbPath = $form->get("thumbnail")->getData();
            if($thumbPath) {
                 $newFileName = uniqid() . "." . $thumbPath->guessExtension();

                try {
                    $thumbPath->move(
                        $this->getparameter("kernel.project_dir") . "/public/uploads", 
                        $newFileName
                    );
                } catch(FileException $e) {
                    return new Response($e->getMessage());
                }

                $newPost->setThumbnail("/uploads/" . $newFileName);
            }
            
            $this->em->persist($newPost);
            $this->em->flush();

            return $this->redirectToRoute("post_index");
        }

        return $this->render('post/create.html.twig', [
            "form" => $form->createView()
        ]);
    }


    #[Route("/blog/{id}", methods: ["GET"], name: "post_single")]
    public function single($id): Response {
        $post = $this->em->getRepository(Post::class)->find($id);
        
        return $this->render('post/single.html.twig', [
            "post" => $post
        ]);
    }


    #[Route("/blog/upravit-clanek/{id}", methods: ["GET", "POST"], name: "post_edit")]
    public function edit($id, Request $request): Response {
        $post = $this->em->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
        $thumbPath = $form->get("thumbnail")->getData();

        if($form->isSubmitted() && $form->isValid()) {
            if($thumbPath) {
                if(file_exists( $this->getParameter("kernel.project_dir") . "/public/" . $post->getThumbnail())) {
                    $this->getParameter(("kernel.project_dir")) . $post->getThumbnail();

                    $newFileName = uniqid() . "." . $thumbPath->guessExtension();

                    try {
                        $thumbPath->move(
                            $this->getparameter("kernel.project_dir") . "/public/uploads", 
                            $newFileName
                        );
                    } catch(FileException $e) {
                        return new Response($e->getMessage());
                    }

                    $post->setThumbnail("/uploads/" . $newFileName);
                    $this->em->flush();
                    return $this->redirectToRoute("post_index");
                }
            }
            else {
                $post->setTitle($form->get("title")->getData());
                $post->setExcerpt($form->get("excerpt")->getData());
                $post->setContent($form->get("content")->getData());
                $post->setPublishDate($form->get("publishDate")->getData());

                $this->em->flush();
                return $this->redirectToRoute("post_index");
            }

        }

        return $this->render('post/edit.html.twig', [
            "post" =>  $post,
            "form" => $form->createView()
        ]);
    }


    #[Route("/blog/smazat-clanek/{id}", methods: ["GET", "DELETE"], name: "post_delete")]
    public function delete($id): Response {
        $post = $this->em->getRepository(Post::class)->find($id);
        $this->em->remove($post);
        $this->em->flush();

        return $this->redirectToRoute("post_index");
    }
}