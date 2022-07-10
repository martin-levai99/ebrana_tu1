<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank(
        message: "Pole nesmí být prázdné"
    )]
    #[Assert\Length(
        min: 3, 
        max: 255,
        minMessage: 'Předmět musí mít alespoň {{ limit }} znaky',
        maxMessage: 'Předmět nesmí být delší než {{ limit }} znaků'
    )]
    private $subject;

    #[ORM\Column(type: 'string', length: 4000, nullable: true)]
    #[Assert\NotBlank(
        message: "Pole nesmí být prázdné"
    )]
    #[Assert\Length(
        min: 10, 
        max: 4000,
        minMessage: 'Obsah musí mít alespoň {{ limit }} znaků',
        maxMessage: 'Obsah nesmí být delší než {{ limit }} znaků'
    )]
    private $content;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank(
        message: "Pole nesmí být prázdné"
    )]
    #[Assert\Email(
        message: 'Tento email {{ value }} není validní.',
    )]
    private $email;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $publish_datetime;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'comments')]
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPublishDateTime(): ?\DateTimeInterface
    {
        return $this->publish_datetime;
    }

    public function setPublishDateTime(?\DateTimeInterface $publish_datetime): self
    {
        $this->publish_datetime = $publish_datetime;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
