<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=JoueurRepository::class)
 */
class Joueur
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
     * @var string
     *@Assert\NotBlank(message="remplir le champs mot de passe")
     *  @Assert\Length(
     *      min = 8,
     *      max = 30,
     *      minMessage = "mot de passe doit contenir au moins 8 carracteres",
     *      maxMessage = "mot de passe doit contenir au maximum 30 carracteres"
     * )
     * @ORM\Column(name="password", type="string", length=80, nullable=false)
     */
    private $password;

    /**
     * @var string
     *@Assert\NotBlank(message="remplir le champs nom")

     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=Jeu::class, mappedBy="joueur")
     */
    private $jeux;
    /**

     * @var string|null
     */
    public string $code;
    /**

     * @var string|null

     */
    public string $password1;
    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255, nullable=true)

     */
    private $image;


    /**
     * @var string
     *@Assert\NotBlank(message="remplir le champs pseudo")

     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 1})
     */
    private $Niveau;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Emplacement;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lng;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lat;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Country;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $Dateinscription;

    /**
     * @ORM\OneToOne(targetEntity=Historiquedeconnection::class, mappedBy="Joueur", cascade={"persist", "remove"})
     */
    private $historiquedeconnection;

    public function __construct()
    {
        $this->jeux = new ArrayCollection();
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

    public function setEmail(string $email)
    {
        $this->email = $email;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }
    /**
     * @return Collection|Jeu[]
     */
    public function getJeux(): Collection
    {
        return $this->jeux;
    }

    public function addJeux(Jeu $jeux): self
    {
        if (!$this->jeux->contains($jeux)) {
            $this->jeux[] = $jeux;
            $jeux->addJoueur($this);
        }

        return $this;
    }

    public function removeJeux(Jeu $jeux): self
    {
        if ($this->jeux->removeElement($jeux)) {
            $jeux->removeJoueur($this);
        }

        return $this;
    }
    /**
     * @return string
     */
    public function getPassword1(): string
    {
        return $this->password1;
    }

    /**
     * @param string $password1
     */
    public function setPassword1(string $password1): void
    {
        $this->password1 = $password1;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->Niveau;
    }

    public function setNiveau(?int $Niveau): self
    {
        $this->Niveau = $Niveau;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->Emplacement;
    }

    public function setEmplacement(?string $Emplacement): self
    {
        $this->Emplacement = $Emplacement;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param mixed $lng
     */
    public function setLng($lng): void
    {
        $this->lng = $lng;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat): void
    {
        $this->lat = $lat;
    }



    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(?string $Country): self
    {
        $this->Country = $Country;

        return $this;
    }

    public function getDateinscription(): ?\DateTimeInterface
    {
        return $this->Dateinscription;
    }

    public function setDateinscription(?\DateTimeInterface $Dateinscription): self
    {
        $this->Dateinscription = $Dateinscription;

        return $this;
    }

    public function getHistoriquedeconnection(): ?Historiquedeconnection
    {
        return $this->historiquedeconnection;
    }

    public function setHistoriquedeconnection(?Historiquedeconnection $historiquedeconnection): self
    {
        // unset the owning side of the relation if necessary
        if ($historiquedeconnection === null && $this->historiquedeconnection !== null) {
            $this->historiquedeconnection->setJoueur(null);
        }

        // set the owning side of the relation if necessary
        if ($historiquedeconnection !== null && $historiquedeconnection->getJoueur() !== $this) {
            $historiquedeconnection->setJoueur($this);
        }

        $this->historiquedeconnection = $historiquedeconnection;

        return $this;
    }
}
