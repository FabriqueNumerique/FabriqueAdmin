<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApprenantRepository")
 */
class Apprenant extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Prenom;

    // /**
    //  * @ORM\Column(type="string", length=255)
    //  */
    // private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Tel;

    /**
     * @ORM\Column(type="date")
     */
    private $DateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Git;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Avatar;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Promotion", mappedBy="apprenants")
     */
    private $Promotion;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reseau", mappedBy="Apprenant", orphanRemoval=true)
     */
    private $reseaux;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Offres", mappedBy="Apprenant", cascade={"persist", "remove"})
     */
    private $offre;

    public function __construct()
    {
        $this->Promotion = new ArrayCollection();
        $this->reseaux = new ArrayCollection();
    }

    public function getFullname()
    {
        return "{$this->Prenom} {$this->Nom}";
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    // public function getEmail(): ?string
    // {
    //     return $this->Email;
    // }

    // public function setEmail(string $Email): self
    // {
    //     $this->Email = $Email;

    //     return $this;
    // }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->DateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $DateNaissance): self
    {
        $this->DateNaissance = $DateNaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getGit(): ?string
    {
        return $this->Git;
    }

    public function setGit(?string $Git): self
    {
        $this->Git = $Git;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->Avatar;
    }

    public function setAvatar(?string $Avatar): self
    {
        $this->Avatar = $Avatar;

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotion(): Collection
    {
        return $this->Promotion;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->Promotion->contains($promotion)) {
            $this->Promotion[] = $promotion;
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->Promotion->contains($promotion)) {
            $this->Promotion->removeElement($promotion);
        }

        return $this;
    }


    /**
     * @return Collection|Reseau[]
     */
    public function getReseaux(): Collection
    {
        return $this->reseaux;
    }

    public function addReseaux(Reseau $reseaux): self
    {
        if (!$this->reseaux->contains($reseaux)) {
            $this->reseaux[] = $reseaux;
            $reseaux->setApprenant($this);
        }

        return $this;
    }

    public function removeReseaux(Reseau $reseaux): self
    {
        if ($this->reseaux->contains($reseaux)) {
            $this->reseaux->removeElement($reseaux);
            // set the owning side to null (unless already changed)
            if ($reseaux->getApprenant() === $this) {
                $reseaux->setApprenant(null);
            }
        }

        return $this;
    }

    public function getOffre(): ?Offres
    {
        return $this->offre;
    }

    public function setOffre(?Offres $offre): self
    {
        $this->offre = $offre;

        // set (or unset) the owning side of the relation if necessary
        $newApprenant = null === $offre ? null : $this;
        if ($offre->getApprenant() !== $newApprenant) {
            $offre->setApprenant($newApprenant);
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->Nom.' '.(string) $this->Prenom;
    }
}
