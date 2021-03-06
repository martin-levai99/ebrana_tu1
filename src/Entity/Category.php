<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
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

    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'categories')]
    private $posts;

    #[ORM\Column(type: 'string', length: 999, nullable: true)]
    #[Assert\Length(
        min: 3, 
        max: 999,
        minMessage: 'Obsah musí mít alespoň {{ limit }} znaky',
        maxMessage: 'Obsah nesmí být delší než {{ limit }} znaků'
    )]
    private $description;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
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

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            $post->removeCategory($this);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
