<?php

namespace App\Entity;

use App\Repository\SpeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpeRepository::class)]
class Spe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom doit faire plus de 1 caractère", maxMessage:"Le nom ne doit pas faire plus de 255 caractères")]
    private ?string $nom = null;

    #[ORM\OneToOne(inversedBy: 'spe', cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    #[ORM\ManyToOne(inversedBy: 'spe')]
    private ?Classe $classe = null;

    #[ORM\ManyToOne(inversedBy: 'spes')]
    private ?SpeRole $speRole = null;

    #[ORM\OneToMany(mappedBy: 'spe', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    public function getSpeRole(): ?SpeRole
    {
        return $this->speRole;
    }

    public function setSpeRole(?SpeRole $speRole): static
    {
        $this->speRole = $speRole;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSpe($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSpe() === $this) {
                $user->setSpe(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->image;
    }
}
