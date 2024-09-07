<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?float $frequency = null;

    #[ORM\ManyToMany(targetEntity: Day::class, inversedBy: 'tasks')]
    private Collection $scheduled;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Worker $assigned_to = null;

    public function __construct()
    {
        $this->scheduled = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getFrequency(): ?float
    {
        return $this->frequency;
    }

    public function setFrequency(float $frequency): static
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * @return Collection<int, Day>
     */
    public function getScheduled(): Collection
    {
        return $this->scheduled;
    }

    public function addScheduled(Day $scheduled): static
    {
        if (!$this->scheduled->contains($scheduled)) {
            $this->scheduled->add($scheduled);
        }

        return $this;
    }

    public function removeScheduled(Day $scheduled): static
    {
        $this->scheduled->removeElement($scheduled);

        return $this;
    }

    public function getAssignedTo(): ?Worker
    {
        return $this->assigned_to;
    }

    public function setAssignedTo(?Worker $assigned_to): static
    {
        $this->assigned_to = $assigned_to;

        return $this;
    }
}
