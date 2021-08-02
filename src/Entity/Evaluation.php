<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvaluationRepository::class)
 */
class Evaluation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addstatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $q1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $q2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $q3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $q4;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $q5;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $q6;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $q7;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $q8;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $q9;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $semester;


    public function getId(): ?int
    {
        return $this->id;
    }
       public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAddstatus(): ?string
    {
        return $this->addstatus;
    }

    public function setAddstatus(string $addstatus): self
    {
        $this->addstatus = $addstatus;

        return $this;
    }

    public function getQ1(): ?string
    {
        return $this->q1;
    }

    public function setQ1(string $q1): self
    {
        $this->q1 = $q1;

        return $this;
    }

    public function getQ2(): ?string
    {
        return $this->q2;
    }

    public function setQ2(string $q2): self
    {
        $this->q2 = $q2;

        return $this;
    }

    public function getQ3(): ?string
    {
        return $this->q3;
    }

    public function setQ3(string $q3): self
    {
        $this->q3 = $q3;

        return $this;
    }

    public function getQ4(): ?string
    {
        return $this->q4;
    }

    public function setQ4(string $q4): self
    {
        $this->q4 = $q4;

        return $this;
    }

    public function getQ5(): ?string
    {
        return $this->q5;
    }

    public function setQ5(string $q5): self
    {
        $this->q5 = $q5;

        return $this;
    }

    public function getQ6(): ?string
    {
        return $this->q6;
    }

    public function setQ6(string $q6): self
    {
        $this->q6 = $q6;

        return $this;
    }

    public function getQ7(): ?string
    {
        return $this->q7;
    }

    public function setQ7(string $q7): self
    {
        $this->q7 = $q7;

        return $this;
    }

    public function getQ8(): ?string
    {
        return $this->q8;
    }

    public function setQ8(string $q8): self
    {
        $this->q8 = $q8;

        return $this;
    }

    public function getQ9(): ?string
    {
        return $this->q9;
    }

    public function setQ9(string $q9): self
    {
        $this->q9 = $q9;

        return $this;
    }

    public function getSemester(): ?string
    {
        return $this->semester;
    }

    public function setSemester(?string $semester): self
    {
        $this->semester = $semester;

        return $this;
    }


}
