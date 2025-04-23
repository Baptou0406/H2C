<?php
namespace App\Entity;
use App\Repository\ConsommableRepository;
use App\Entity\Logement;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsommableRepository::class)]
class Consommable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_magasin = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\ManyToOne(inversedBy: 'consommable')]
    private ?Logement $logement = null;
    
    // Ajout du champ date
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMagasin(): ?string
    {
        return $this->nom_magasin;
    }

    public function setNomMagasin(string $nom_magasin): static
    {
        $this->nom_magasin = $nom_magasin;
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

    public function getLogement(): ?Logement
    {
        return $this->logement;
    }

    public function setLogement(?Logement $logement): static
    {
        $this->logement = $logement;
        return $this;
    }
    
    // Getter et setter pour le champ date
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getInformation(): array
    {
        return[
            'id' => $this->getId(),
            'Nom magasin' => $this->getNomMagasin(),
            'prix'=> $this->getPrix(),
            'logement'=> $this->getLogement()->getNom(),
            'date' => $this->getDate() ? $this->getDate()->format('d-m-Y') : null,
        ];
    }
}