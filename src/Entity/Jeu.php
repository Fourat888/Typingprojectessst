<?php

namespace App\Entity;

use App\Repository\JeuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JeuRepository::class)
 */
class Jeu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Joueur::class, inversedBy="jeux")
     */
    private $joueur;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="jeu")
     */
    private $admin;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $partietype;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $temps;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $level;

    public function __construct()
    {
        $this->joueur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|joueur[]
     */
    public function getJoueur(): Collection
    {
        return $this->joueur;
    }

    public function addJoueur(joueur $joueur): self
    {
        if (!$this->joueur->contains($joueur)) {
            $this->joueur[] = $joueur;
        }

        return $this;
    }

    public function removeJoueur(joueur $joueur): self
    {
        $this->joueur->removeElement($joueur);

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getPartietype(): ?string
    {
        return $this->partietype;
    }

    public function setPartietype(string $partietype): self
    {
        $this->partietype = $partietype;

        return $this;
    }

    public function getTemps(): ?string
    {
        return $this->temps;
    }

    public function setTemps(string $temps): self
    {
        $this->temps = $temps;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }
}
