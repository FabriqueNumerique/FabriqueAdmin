<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReseauRepository")
 */
class Reseau
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
    private $Lien;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Apprenant", inversedBy="reseaux")
     */
    private $Apprenant;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLien(): ?string
    {
        return $this->Lien;
    }

    public function setLien(string $Lien): self
    {
        $this->Lien = $Lien;

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

    public function __toString()
    {
        return (string) $this->getNom();
    }
}
