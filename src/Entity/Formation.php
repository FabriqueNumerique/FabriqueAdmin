<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormationRepository")
 */
class Formation
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
    private $Intitule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Duree;

    /**
     * @ORM\Column(type="integer")
     */
    private $NombreHeure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Diplome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Modalites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Promotion", mappedBy="Formation")
     */
    private $promotions;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->Intitule;
    }

    public function setIntitule(string $Intitule): self
    {
        $this->Intitule = $Intitule;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->Duree;
    }

    public function setDuree(string $Duree): self
    {
        $this->Duree = $Duree;

        return $this;
    }

    public function getNombreHeure(): ?int
    {
        return $this->NombreHeure;
    }

    public function setNombreHeure(int $NombreHeure): self
    {
        $this->NombreHeure = $NombreHeure;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->Diplome;
    }

    public function setDiplome(string $Diplome): self
    {
        $this->Diplome = $Diplome;

        return $this;
    }

    public function getModalites(): ?string
    {
        return $this->Modalites;
    }

    public function setModalites(?string $Modalites): self
    {
        $this->Modalites = $Modalites;

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->setFormation($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->contains($promotion)) {
            $this->promotions->removeElement($promotion);
            // set the owning side to null (unless already changed)
            if ($promotion->getFormation() === $this) {
                $promotion->setFormation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->Intitule;
    }


}
