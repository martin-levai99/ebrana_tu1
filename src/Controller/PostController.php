<?php 

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Comment;

use App\Form\PostFormType;
use App\Form\CommentFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
                    return $this->redirectToRoute("post_single", ["id" => $post->getId()]);
                }
            }
            else {
                $post->setTitle($form->get("title")->getData());
                $post->setExcerpt($form->get("excerpt")->getData());
                $post->setContent($form->get("content")->getData());
                $post->setPublishDate($form->get("publishDate")->getData());

                $this->em->flush();
                return $this->redirectToRoute("post_single", ["id" => $post->getId()]);
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


    #[Route("/blog/{id}", methods: ["GET", "POST"], name: "post_single")]
    public function single($id, Request $request): Response {
        $post = $this->em->getRepository(Post::class)->find($id);

        // Handle comment form
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()) {

            // Add post_id relationship and current publish datetime
            $newComment = $commentForm->getData();
            $newComment->setPost($post);
            $curr_time = new \DateTime("now");
            $newComment->setPublishDateTime($curr_time);

            // Update
            $this->em->persist($newComment);
            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute("post_single", ["id" => $id]);
        }


        // Get comments for related to this post
        $comments = $this->em->getRepository(Comment::class)->findBy(
            ["post" => $id],
            ['publish_datetime' => 'DESC'],
            100 
        );

        
        

        return $this->render('post/single.html.twig', [
            "post" => $post,
            "commentForm" => $commentForm->createView(),
            "comments" => $comments
        ]);
    }


    public function itemList($max = 4, $offset = 0, $size = "big", $cat_id = null): Response {
        // Prepare POST repository
        $post_repo = $this->em->getRepository(Post::class);


        if($cat_id != null) {
            // Get posts related to $cat_id
            $cat_object = $this->em->getRepository(Category::class)->find($cat_id);
            $posts = $post_repo->getPostsByCategory($cat_object, $max);
        }
        else {
            // Get posts
            $posts = $post_repo->findBy(
                [],
                ['publishDate' => 'DESC'],
                $max,
                $offset
            );
        }

        return $this->render('post/components/itemList.html.twig', [
            "posts" => $posts,
            "size" => $size
        ]);
    }
}