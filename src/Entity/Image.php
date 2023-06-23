<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @ORM\Column(length: 255)
     * @Vich\UploadableField(mapping="image_mapping", fileNameProperty="path")
     */
    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\OneToOne(mappedBy: 'image', cascade: ['persist', 'remove'])]
    private ?Classe $classe = null;

    #[ORM\OneToMany(mappedBy: 'image', targetEntity: SpeRole::class)]
    private Collection $speRoles;

    #[ORM\OneToOne(mappedBy: 'image', cascade: ['persist', 'remove'])]
    //#[ORM\JoinColumn(nullable: true)]
    private ?Spe $spe = null;

    #[ORM\OneToOne(mappedBy: 'avatar', cascade: ['persist', 'remove'])]
    //#[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    public function __construct()
    {
        $this->speRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): static
    {
        // unset the owning side of the relation if necessary
        if ($classe === null && $this->classe !== null) {
            $this->classe->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($classe !== null && $classe->getImage() !== $this) {
            $classe->setImage($this);
        }

        $this->classe = $classe;

        return $this;
    }

    /**
     * @return Collection<int, SpeRole>
     */
    public function getSpeRoles(): Collection
    {
        return $this->speRoles;
    }

    public function addSpeRole(SpeRole $speRole): static
    {
        if (!$this->speRoles->contains($speRole)) {
            $this->speRoles->add($speRole);
            $speRole->setImage($this);
        }

        return $this;
    }

    public function removeSpeRole(SpeRole $speRole): static
    {
        if ($this->speRoles->removeElement($speRole)) {
            // set the owning side to null (unless already changed)
            if ($speRole->getImage() === $this) {
                $speRole->setImage(null);
            }
        }

        return $this;
    }

    public function getSpe(): ?Spe
    {
        return $this->spe;
    }

    public function setSpe(?Spe $spe): static
    {
        // unset the owning side of the relation if necessary
        if ($spe === null && $this->spe !== null) {
            $this->spe->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($spe !== null && $spe->getImage() !== $this) {
            $spe->setImage($this);
        }

        $this->spe = $spe;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setAvatar(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getAvatar() !== $this) {
            $user->setAvatar($this);
        }

        $this->user = $user;

        return $this;
    }

    public function __toString()
    {
        return $this->path;
    }
}
