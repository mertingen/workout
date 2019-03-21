<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlanRepository")
 */
class Plan implements \JsonSerializable
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $difficulty;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlanDay", mappedBy="plan", orphanRemoval=true)
     */
    private $planDays;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserPlan", mappedBy="plan", orphanRemoval=true)
     */
    private $userPlans;

    public function __construct()
    {
        $this->planDays = new ArrayCollection();
        $this->userPlans = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * @return Collection|PlanDay[]
     */
    public function getPlanDays(): Collection
    {
        return $this->planDays;
    }

    public function addPlanDay(PlanDay $planDay): self
    {
        if (!$this->planDays->contains($planDay)) {
            $this->planDays[] = $planDay;
            $planDay->setPlan($this);
        }

        return $this;
    }

    public function removePlanDay(PlanDay $planDay): self
    {
        if ($this->planDays->contains($planDay)) {
            $this->planDays->removeElement($planDay);
            // set the owning side to null (unless already changed)
            if ($planDay->getPlan() === $this) {
                $planDay->setPlan(null);
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
            'description' => $this->getDescription(),
            'difficulty' => $this->getDifficulty()
        );
    }

    /**
     * @return Collection|UserPlan[]
     */
    public function getUserPlans(): Collection
    {
        return $this->userPlans;
    }

    public function addUserPlan(UserPlan $userPlan): self
    {
        if (!$this->userPlans->contains($userPlan)) {
            $this->userPlans[] = $userPlan;
            $userPlan->setRule($this);
        }

        return $this;
    }

    public function removeUserPlan(UserPlan $userPlan): self
    {
        if ($this->userPlans->contains($userPlan)) {
            $this->userPlans->removeElement($userPlan);
            // set the owning side to null (unless already changed)
            if ($userPlan->getRule() === $this) {
                $userPlan->setRule(null);
            }
        }

        return $this;
    }
}
