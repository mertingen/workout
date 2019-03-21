<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserPlanRepository")
 */
class UserPlan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_confirmation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userPlans")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plan", inversedBy="userPlans")
     */
    private $plan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsConfirmation(): ?bool
    {
        return $this->is_confirmation;
    }

    public function setIsConfirmation(bool $is_confirmation): self
    {
        $this->is_confirmation = $is_confirmation;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
