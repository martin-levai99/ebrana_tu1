<?php 

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route("/komentar/smazat/{id}", methods: ["GET", "DELETE"], name: "comment_delete")]
    public function delete($id): Response {

        $comment = $this->em->getRepository(Comment::class)->find($id);
        $this->em->remove($comment);
        $this->em->flush();

        return $this->redirectToRoute("post_index");

    }
}