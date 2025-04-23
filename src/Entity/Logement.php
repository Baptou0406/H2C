<?php

namespace App\Entity;

use App\Repository\LogementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogementRepository::class)]
class Logement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column]
    private ?int $commission = null;
 
    #[ORM\Column]
    private ?int $menage = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'logement_id')]
    private Collection $logement_id;

    /**
     * @var Collection<int, Intervention>
     */
    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: Intervention::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $interventions;

    /**
     * @var Collection<int, Consommable>
     */
    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: Consommable::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $consommables;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: Reservation::class, cascade: ['remove'], orphanRemoval: true)]
private Collection $reservations;


    /**
     * @var Collection<int, Test>
     */
    #[ORM\OneToMany(targetEntity: Test::class, mappedBy: 'logement')]
    private Collection $tests;
    

    public function __construct()
    {
        $this->logement_id = new ArrayCollection();
        $this->interventions = new ArrayCollection();
        $this->consommables = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->tests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getCommission(): ?int
    {
        return $this->commission;
    }

    public function setCommission(int $commission): static
    {
        $this->commission = $commission;

        return $this;
    }

    public function getMenage(): ?int
    {
        return $this->menage;
    }

    public function setMenage(int $menage): static
    {
        $this->menage = $menage;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getLogementId(): Collection
    {
        return $this->logement_id;
    }

    public function addLogementId(Reservation $logementId): static
    {
        if (!$this->logement_id->contains($logementId)) {
            $this->logement_id->add($logementId);
            $logementId->setLogementId($this);
        }

        return $this;
    }

    public function removeLogementId(Reservation $logementId): static
    {
        if ($this->logement_id->removeElement($logementId)) {
            // set the owning side to null (unless already changed)
            if ($logementId->getLogementId() === $this) {
                $logementId->setLogementId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): static
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->setLogementId($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getLogementId() === $this) {
                $intervention->setLogementId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Consommable>
     */
    public function getConsommables(): Collection
    {
        return $this->consommables;
    }

    public function addConsommable(Consommable $consommable): static
    {
        if (!$this->consommables->contains($consommable)) {
            $this->consommables->add($consommable);
            $consommable->setLogementId($this);
        }

        return $this;
    }

    public function removeConsommable(Consommable $consommable): static
    {
        if ($this->consommables->removeElement($consommable)) {
            // set the owning side to null (unless already changed)
            if ($consommable->getLogementId() === $this) {
                $consommable->setLogementId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setLogementId($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getLogementId() === $this) {
                $reservation->setLogementId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Test>
     */
    public function getTests(): Collection
    {
        return $this->tests;
    }

    public function addTest(Test $test): static
    {
        if (!$this->tests->contains($test)) {
            $this->tests->add($test);
            $test->setLogement($this);
        }

        return $this;
    }

    public function removeTest(Test $test): static
    {
        if ($this->tests->removeElement($test)) {
            // set the owning side to null (unless already changed)
            if ($test->getLogement() === $this) {
                $test->setLogement(null);
            }
        }

        return $this;
    }
    public function getInformation(): array
    {
        return[
            'id' => $this->getId(),
            'Nom' => $this->getNom(),
            'Commission'=> $this->getCommission(),
            'Menage'=> $this->getMenage(),
        ];
    }
}
