<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: "Pole nesmí být prázdné"
    )]
    #[Assert\Length(
        min: 3, 
        max: 255,
        minMessage: 'Název musí mít alespoň {{ limit }} znaky',
        maxMessage: 'Název nesmí být delší než {{ limit }} znaků'
    )]
    private $title;

    #[ORM\Column(type: 'string', length: 1000)]
    #[Assert\NotBlank(
        message: "Pole nesmí být prázdné"
    )]
    #[Assert\Length(
        min: 3, 
        max: 1000,
        minMessage: 'Popisek musí mít alespoň {{ limit }} znaky',
        maxMessage: 'Popisek nesmí být delší než {{ limit }} znaků'
    )]
    private $excerpt;

    #[ORM\Column(type: 'string', length: 9999)]
    #[Assert\NotBlank(
        message: "Pole nesmí být prázdné"
    )]
    #[Assert\Length(
        min: 3, 
        max: 9999,
        minMessage: 'Obsah musí mít alespoň {{ limit }} znaky',
        maxMessage: 'Obsah nesmí být delší než {{ limit }} znaků'
    )]
    private $content;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(
        message: "Pole nesmí být prázdné"
    )]
    private $publishDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $thumbnail;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'posts')]
    private $categories;

    #[ORM\OneToMany(mappedBy: 'yes', targetEntity: Comment::class)]
    private $comments;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
}
