<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;


#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom doit faire plus de 1 caractère", maxMessage:"Le nom ne doit pas faire plus de 255 caractères")]
    private ?string $nom = null;

    #[ORM\OneToOne(inversedBy: 'classe', cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Spe::class)]
    private Collection $spe;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->spe = new ArrayCollection();
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

    /**
     * @return Collection<int, Spe>
     */
    public function getSpe(): Collection
    {
        return $this->spe;
    }

    public function addSpe(Spe $spe): static
    {
        if (!$this->spe->contains($spe)) {
            $this->spe->add($spe);
            $spe->setClasse($this);
        }

        return $this;
    }

    public function removeSpe(Spe $spe): static
    {
        if ($this->spe->removeElement($spe)) {
            // set the owning side to null (unless already changed)
            if ($spe->getClasse() === $this) {
                $spe->setClasse(null);
            }
        }

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
            $user->setClasse($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClasse() === $this) {
                $user->setClasse(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
