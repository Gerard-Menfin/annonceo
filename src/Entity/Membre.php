<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MembreRepository")
 * @UniqueEntity(fields="pseudo", message="Le pseudo existe déjà dans la base de données")
 */
class Membre implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $civilite;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_enregistrement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="membre", orphanRemoval=true)
     */
    private $annonces;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="membre_notant", orphanRemoval=true)
     */
    private $notes_donnees;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="membre_note")
     */
    private $notes_recues;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->notes_donnees = new ArrayCollection();
        $this->notes_recues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->date_enregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $date_enregistrement): self
    {
        $this->date_enregistrement = $date_enregistrement;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setMembre($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getMembre() === $this) {
                $annonce->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotesDonnees(): Collection
    {
        return $this->notes_donnees;
    }

    public function addNotesDonnee(Note $notesDonnee): self
    {
        if (!$this->notes_donnees->contains($notesDonnee)) {
            $this->notes_donnees[] = $notesDonnee;
            $notesDonnee->setMembreNotant($this);
        }

        return $this;
    }

    public function removeNotesDonnee(Note $notesDonnee): self
    {
        if ($this->notes_donnees->contains($notesDonnee)) {
            $this->notes_donnees->removeElement($notesDonnee);
            // set the owning side to null (unless already changed)
            if ($notesDonnee->getMembreNotant() === $this) {
                $notesDonnee->setMembreNotant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotesRecues(): Collection
    {
        return $this->notes_recues;
    }

    public function addNotesRecue(Note $notesRecue): self
    {
        if (!$this->notes_recues->contains($notesRecue)) {
            $this->notes_recues[] = $notesRecue;
            $notesRecue->setMembreNote($this);
        }

        return $this;
    }

    public function removeNotesRecue(Note $notesRecue): self
    {
        if ($this->notes_recues->contains($notesRecue)) {
            $this->notes_recues->removeElement($notesRecue);
            // set the owning side to null (unless already changed)
            if ($notesRecue->getMembreNote() === $this) {
                $notesRecue->setMembreNote(null);
            }
        }

        return $this;
    }
}
