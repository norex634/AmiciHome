<?php

namespace App\Entity;

use App\Repository\ApplyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ApplyRepository::class)]
class Apply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom doit faire plus de 1 caractère", maxMessage:"Le nom ne doit pas faire plus de 255 caractères")]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $log = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $why = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $exp = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 30, minMessage: "Le btag doit faire plus de 1 caractère", maxMessage:"Le btg ne doit pas faire plus de 30 caractères")]
    private ?string $btag = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 30, minMessage: "Le tag discord doit faire plus de 1 caractère", maxMessage:"Le tag discord ne doit pas faire plus de 30 caractères")]
    private ?string $discord = null;

    #[ORM\ManyToOne(inversedBy: 'applies')]
    private ?User $User = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'apply', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLog(): ?string
    {
        return $this->log;
    }

    public function setLog(?string $log): static
    {
        $this->log = $log;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): static
    {
        $this->about = $about;

        return $this;
    }

    public function getWhy(): ?string
    {
        return $this->why;
    }

    public function setWhy(?string $why): static
    {
        $this->why = $why;

        return $this;
    }

    public function getExp(): ?string
    {
        return $this->exp;
    }

    public function setExp(?string $exp): static
    {
        $this->exp = $exp;

        return $this;
    }

    public function getBtag(): ?string
    {
        return $this->btag;
    }

    public function setBtag(string $btag): static
    {
        $this->btag = $btag;

        return $this;
    }

    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    public function setDiscord(string $discord): static
    {
        $this->discord = $discord;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setApply($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getApply() === $this) {
                $comment->setApply(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom; // Replace 'name' with the actual property you want to display
    }
}
