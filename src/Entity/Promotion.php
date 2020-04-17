<?php

namespace App\Entity;

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
     * @ORM\Column(type="integer")
     */
    private $NombreEleves;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Commentaires;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formation", inversedBy="promotions")
     */
    private $Formation;

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

    public function getNombreEleves(): ?int
    {
        return $this->NombreEleves;
    }

    public function setNombreEleves(int $NombreEleves): self
    {
        $this->NombreEleves = $NombreEleves;

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
}
