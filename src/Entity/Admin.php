<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *@Assert\NotBlank(message="remplir le champs email")
     * @Assert\Email(
     *     message = "email '{{ value }}' n'est pas un email valide"
     * )
     * @ORM\Column(name="email", type="string", length=30, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;


    /**

     * @var string|null

     */
    public string $password1;

    /**

     * @var string|null

     */
    public string $password2;
    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255, nullable=true)

     */
    private $image;
    /**
     * @ORM\OneToMany(targetEntity=Jeu::class, mappedBy="admin")
     */
    private $jeu;

    /**
     * @var string
     *@Assert\NotBlank(message="remplir le champs nom")

     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *@Assert\NotBlank(message="remplir le champs prenom")

     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     */
    private $prenom;

    public function __construct()
    {
        $this->jeu = new ArrayCollection();
    }
    public function getImage()
    {
        return $this->image;
    }


    public function setImage( $image)
    {
        $this->image = $image;
        return $this;

    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $Email): self
    {
        $this->email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword1(): string
    {
        return $this->password1;
    }


    public function setPassword1(string $password1): void
    {
        $this->password1 = $password1;
    }

    public function getPassword2(): string
    {
        return $this->password2;
    }


    public function setPassword2(string $password2): void
    {
        $this->password2 = $password2;
    }
    /**
     * @return Collection|jeu[]
     */
    public function getJeu(): Collection
    {
        return $this->jeu;
    }

    public function addJeu(jeu $jeu): self
    {
        if (!$this->jeu->contains($jeu)) {
            $this->jeu[] = $jeu;
            $jeu->setAdmin($this);
        }

        return $this;
    }

    public function removeJeu(jeu $jeu): self
    {
        if ($this->jeu->removeElement($jeu)) {
            // set the owning side to null (unless already changed)
            if ($jeu->getAdmin() === $this) {
                $jeu->setAdmin(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
}
