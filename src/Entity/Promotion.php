<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromotionRepository")
 */
class Promotion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Annee;

    /**
     * @ORM\Column(type="date")
     */
    private $DateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $DateFin;

    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Commentaires;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formation", inversedBy="promotions")
     */
    private $Formation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Apprenant", inversedBy="Promotion")
     */
    private $apprenants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Retard", mappedBy="promotion", orphanRemoval=true)
     */
    private $retards;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->retards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?int
    {
        return $this->Annee;
    }

    public function setAnnee(int $Annee): self
    {
        $this->Annee = $Annee;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }



    public function getCommentaires(): ?string
    {
        return $this->Commentaires;
    }

    public function setCommentaires(?string $Commentaires): self
    {
        $this->Commentaires = $Commentaires;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->Formation;
    }

    public function setFormation(?Formation $Formation): self
    {
        $this->Formation = $Formation;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->addPromotion($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            $apprenant->removePromotion($this);
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->Annee.' '.$this->Formation;
    }

    /**
     * @return Collection|Retard[]
     */
    public function getRetards(): Collection
    {
        return $this->retards;
    }

    public function addRetard(Retard $retard): self
    {
        if (!$this->retards->contains($retard)) {
            $this->retards[] = $retard;
            $retard->setPromotion($this);
        }

        return $this;
    }

    public function removeRetard(Retard $retard): self
    {
        if ($this->retards->contains($retard)) {
            $this->retards->removeElement($retard);
            // set the owning side to null (unless already changed)
            if ($retard->getPromotion() === $this) {
                $retard->setPromotion(null);
            }
        }

        return $this;
    }

    
}
