<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlanDayRepository")
 */
class PlanDay implements \JsonSerializable
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $planDayOrder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plan", inversedBy="planDays")
     */
    private $plan;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExerciseInstance", mappedBy="exercise")
     */
    private $exerciseInstances;

    public function __construct()
    {
        $this->exerciseInstances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPlanDayOrder(): ?int
    {
        return $this->planDayOrder;
    }

    public function setPlanDayOrder(int $planDayOrder): self
    {
        $this->planDayOrder = $planDayOrder;

        return $this;
    }

    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public function setPlan(?Plan $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * @return Collection|ExerciseInstance[]
     */
    public function getExerciseInstances(): Collection
    {
        return $this->exerciseInstances;
    }

    public function addExerciseInstance(ExerciseInstance $exerciseInstance): self
    {
        if (!$this->exerciseInstances->contains($exerciseInstance)) {
            $this->exerciseInstances[] = $exerciseInstance;
            $exerciseInstance->setExercise($this);
        }

        return $this;
    }

    public function removeExerciseInstance(ExerciseInstance $exerciseInstance): self
    {
        if ($this->exerciseInstances->contains($exerciseInstance)) {
            $this->exerciseInstances->removeElement($exerciseInstance);
            // set the owning side to null (unless already changed)
            if ($exerciseInstance->getExercise() === $this) {
                $exerciseInstance->setExercise(null);
            }
        }

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'planDayOrder' => $this->getPlanDayOrder(),
            'plan' => $this->getPlan()
        );
    }
}
