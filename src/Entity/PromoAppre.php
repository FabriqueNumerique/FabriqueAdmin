<?php

namespace App\Entity;

use App\Repository\PromoAppreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromoAppreRepository::class)
 */
class PromoAppre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="promoAppres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $promotion;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="promoAppres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apprenant;

    /**
     * @ORM\OneToMany(targetEntity=Retard::class, mappedBy="PromoAppre", orphanRemoval=true)
     */
    private $retards;

    /**
     * @ORM\OneToMany(targetEntity=Absence::class, mappedBy="PromoAppre", orphanRemoval=true)
     */
    private $absences;

    public function __construct()
    {
        $this->retards = new ArrayCollection();
        $this->absences = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->promotion->getAnnee().' '. $this->promotion->getFormation()->getIntitule().' '.$this->apprenant;
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
            $retard->setPromoAppre($this);
        }

        return $this;
    }

    public function removeRetard(Retard $retard): self
    {
        if ($this->retards->contains($retard)) {
            $this->retards->removeElement($retard);
            // set the owning side to null (unless already changed)
            if ($retard->getPromoAppre() === $this) {
                $retard->setPromoAppre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Absence[]
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absence $absence): self
    {
        if (!$this->absences->contains($absence)) {
            $this->absences[] = $absence;
            $absence->setPromoAppre($this);
        }

        return $this;
    }

    public function removeAbsence(Absence $absence): self
    {
        if ($this->absences->contains($absence)) {
            $this->absences->removeElement($absence);
            // set the owning side to null (unless already changed)
            if ($absence->getPromoAppre() === $this) {
                $absence->setPromoAppre(null);
            }
        }

        return $this;
    }

   
}
