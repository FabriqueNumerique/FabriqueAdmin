<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AbsenceRepository")
 */
class Absence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $justifie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Apprenant", inversedBy="absences")
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Promotion", inversedBy="absences")
     */
    private $promotion;

    /**
     * @ORM\ManyToOne(targetEntity=PromoAppre::class, inversedBy="absences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PromoAppre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getJustifie(): ?string
    {
        return $this->justifie;
    }

    public function setJustifie(string $justifie): self
    {
        $this->justifie = $justifie;

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

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getPromoAppre(): ?PromoAppre
    {
        return $this->PromoAppre;
    }

    public function setPromoAppre(?PromoAppre $PromoAppre): self
    {
        $this->PromoAppre = $PromoAppre;

        return $this;
    }
}
