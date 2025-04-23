<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom_voyageur = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Logement $logement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomVoyageur(): ?string
    {
        return $this->Nom_voyageur;
    }

    public function setNomVoyageur(string $Nom_voyageur): static
    {
        $this->Nom_voyageur = $Nom_voyageur;
        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;
        return $this;
    }

    public function getLogement(): ?Logement
    {
        return $this->logement;
    }

    public function setLogement(?Logement $logement): static
    {
        $this->logement = $logement;
        return $this;
    }
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
private ?string $plateforme = null;

public function getPlateforme(): ?string
{
    return $this->plateforme;
}

public function setPlateforme(?string $plateforme): self
{
    $this->plateforme = $plateforme;
    return $this;
}


    public function getInformation(): array
    {
        return [
            'id' => $this->getId(),
            'Nom_voyageur' => $this->getNomVoyageur(),
            'Plateforme' => $this->getPlateforme(),
            'date_debut'=> $this->getDate()->format('d-m-Y'),
            'date_fin'=> $this->getDateFin()->format('d-m-Y'),
            'prix'=> $this->getPrix(),
            'logement'=> $this->getLogement()->getNom(),
        ];
    }
}
