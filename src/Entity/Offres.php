<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OffresRepository")
 */
class Offres
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
    private $CahierDesCharges;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Apprenant", inversedBy="offre", cascade={"persist", "remove"})
     */
    private $Apprenant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprise", inversedBy="offres")
     */
    private $Entreprise;

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

    public function getCahierDesCharges(): ?string
    {
        return $this->CahierDesCharges;
    }

    public function setCahierDesCharges(?string $CahierDesCharges): self
    {
        $this->CahierDesCharges = $CahierDesCharges;

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->Apprenant;
    }

    public function setApprenant(?Apprenant $Apprenant): self
    {
        $this->Apprenant = $Apprenant;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->Entreprise;
    }

    public function setEntreprise(?Entreprise $Entreprise): self
    {
        $this->Entreprise = $Entreprise;

        return $this;
    }
}
