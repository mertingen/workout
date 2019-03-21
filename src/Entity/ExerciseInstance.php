<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExerciseInstanceRepository")
 */
class ExerciseInstance implements \JsonSerializable
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
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $instance_order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exercise", inversedBy="exerciseInstances")
     */
    private $exercise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PlanDay", inversedBy="exerciseInstances")
     */
    private $planDay;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getInstanceOrder(): ?int
    {
        return $this->instance_order;
    }

    public function setInstanceOrder(int $instance_order): self
    {
        $this->instance_order = $instance_order;

        return $this;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getPlanDay(): ?PlanDay
    {
        return $this->planDay;
    }

    public function setPlanDay(?PlanDay $planDay): self
    {
        $this->planDay = $planDay;

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
            'duration' => $this->getDuration(),
            'instanceOrder' => $this->getInstanceOrder(),
            'planDay' => $this->getPlanDay(),
            'exercise' => $this->getExercise()
        );
    }
}
