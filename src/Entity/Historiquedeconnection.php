<?php

namespace App\Entity;

use App\Repository\HistoriquedeconnectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoriquedeconnectionRepository::class)
 */
class Historiquedeconnection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Joueur::class, inversedBy="historiquedeconnection", cascade={"persist", "remove"})
     */
    private $Joueur;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $nbfois;

    /**
     * @ORM\Column(type="float", length=255, nullable=true)
     */
    private $nbheures;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $Datedeb;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $Datefin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->Joueur;
    }

    public function setJoueur(?Joueur $Joueur): self
    {
        $this->Joueur = $Joueur;

        return $this;
    }

    public function getNbfois(): ?int
    {
        return $this->nbfois;
    }

    public function setNbfois(int $nbfois): self
    {
        $this->nbfois = $nbfois;

        return $this;
    }

    public function getNbheures(): ?float
    {
        return $this->nbheures;
    }

    public function setNbheures(?float $nbheures): self
    {
        $this->nbheures = $nbheures;

        return $this;
    }

    public function getDatedeb(): ?\DateTimeInterface
    {
        return $this->Datedeb;
    }

    public function setDatedeb(?\DateTimeInterface $Datedeb): self
    {
        $this->Datedeb = $Datedeb;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->Datefin;
    }

    public function setDatefin(?\DateTimeInterface $Datefin): self
    {
        $this->Datefin = $Datefin;

        return $this;
    }
}
