<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RetardRepository")
 */
class Retard
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
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreHeure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $justifie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Apprenant", inversedBy="retards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Promotion", inversedBy="retards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $promotion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNombreHeure(): ?int
    {
        return $this->nombreHeure;
    }

    public function setNombreHeure(int $nombreHeure): self
    {
        $this->nombreHeure = $nombreHeure;

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
}
