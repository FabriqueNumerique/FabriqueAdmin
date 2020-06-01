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
     * @ORM\OneToMany(targetEntity=PromoAppre::class, mappedBy="promotion",  orphanRemoval=true)
     */
    private $promoAppres;

    public function __construct()
    {

        $this->promoAppres = new ArrayCollection();
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

    

    public function __toString()
    {
        return (string) $this->Annee.' '.$this->Formation;
    }

    

    /**
     * @return Collection|PromoAppre[]
     */
    public function getPromoAppres(): Collection
    {
        return $this->promoAppres;
    }

    public function addPromoAppre(PromoAppre $promoAppre): self
    {
        if (!$this->promoAppres->contains($promoAppre)) {
            $this->promoAppres[] = $promoAppre;
            $promoAppre->setPromotion($this);
        }

        return $this;
    }

    public function removePromoAppre(PromoAppre $promoAppre): self
    {
        if ($this->promoAppres->contains($promoAppre)) {
            $this->promoAppres->removeElement($promoAppre);
            // set the owning side to null (unless already changed)
            if ($promoAppre->getPromotion() === $this) {
                $promoAppre->setPromotion(null);
            }
        }

        return $this;
    }

    
}
