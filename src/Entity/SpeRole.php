<?php

namespace App\Entity;

use App\Repository\SpeRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpeRoleRepository::class)]
class SpeRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom doit faire plus de 1 caractère", maxMessage:"Le nom ne doit pas faire plus de 255 caractères")]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'speRoles')]
    private ?Image $image = null;

    #[ORM\OneToMany(mappedBy: 'speRole', targetEntity: Spe::class)]
    private Collection $spes;

    public function __construct()
    {
        $this->spes = new ArrayCollection();
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
    public function getSpes(): Collection
    {
        return $this->spes;
    }

    public function addSpe(Spe $spe): static
    {
        if (!$this->spes->contains($spe)) {
            $this->spes->add($spe);
            $spe->setSpeRole($this);
        }

        return $this;
    }

    public function removeSpe(Spe $spe): static
    {
        if ($this->spes->removeElement($spe)) {
            // set the owning side to null (unless already changed)
            if ($spe->getSpeRole() === $this) {
                $spe->setSpeRole(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
