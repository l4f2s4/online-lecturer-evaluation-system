<?php

namespace App\Entity;

use App\Repository\EvaluateMarkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvaluateMarkRepository::class)
 */
class EvaluateMark
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $mark1;

    /**
     * @ORM\Column(type="float")
     */
    private $mark2;

    /**
     * @ORM\Column(type="float")
     */
    private $mark3;

    /**
     * @ORM\Column(type="float")
     */
    private $mark4;

    /**
     * @ORM\Column(type="float")
     */
    private $mark5;

    /**
     * @ORM\Column(type="float")
     */
    private $mark6;

    /**
     * @ORM\Column(type="float")
     */
    private $mark7;

    /**
     * @ORM\Column(type="float")
     */
    private $mark8;

    /**
     * @ORM\Column(type="float")
     */
    private $mark9;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="addMark")
     */
    private $studentMark;

    /**
     * @ORM\ManyToMany(targetEntity=Program::class)
     */
    private $programMark;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="float")
     */
    private $avg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $academicyear;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    public function __construct()
    {
        $this->programMark = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark1(): ?float
    {
        return $this->mark1;
    }

    public function setMark1(float $mark1): self
    {
        $this->mark1 = $mark1;

        return $this;
    }

    public function getMark2(): ?float
    {
        return $this->mark2;
    }

    public function setMark2(float $mark2): self
    {
        $this->mark2 = $mark2;

        return $this;
    }

    public function getMark3(): ?float
    {
        return $this->mark3;
    }

    public function setMark3(float $mark3): self
    {
        $this->mark3 = $mark3;

        return $this;
    }

    public function getMark4(): ?float
    {
        return $this->mark4;
    }

    public function setMark4(float $mark4): self
    {
        $this->mark4 = $mark4;

        return $this;
    }

    public function getMark5(): ?float
    {
        return $this->mark5;
    }

    public function setMark5(float $mark5): self
    {
        $this->mark5 = $mark5;

        return $this;
    }

    public function getMark6(): ?float
    {
        return $this->mark6;
    }

    public function setMark6(float $mark6): self
    {
        $this->mark6 = $mark6;

        return $this;
    }

    public function getMark7(): ?float
    {
        return $this->mark7;
    }

    public function setMark7(float $mark7): self
    {
        $this->mark7 = $mark7;

        return $this;
    }

    public function getMark8(): ?float
    {
        return $this->mark8;
    }

    public function setMark8(float $mark8): self
    {
        $this->mark8 = $mark8;

        return $this;
    }

    public function getMark9(): ?float
    {
        return $this->mark9;
    }

    public function setMark9(float $mark9): self
    {
        $this->mark9 = $mark9;

        return $this;
    }

    public function getStudentMark(): ?User
    {
        return $this->studentMark;
    }

    public function setStudentMark(?User $studentMark): self
    {
        $this->studentMark = $studentMark;

        return $this;
    }

    /**
     * @return Collection|Program[]
     */
    public function getProgramMark(): Collection
    {
        return $this->programMark;
    }

    public function addProgramMark(Program $programMark): self
    {
        if (!$this->programMark->contains($programMark)) {
            $this->programMark[] = $programMark;
        }

        return $this;
    }

    public function removeProgramMark(Program $programMark): self
    {
        $this->programMark->removeElement($programMark);

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getAvg(): ?float
    {
        return $this->avg;
    }

    public function setAvg(float $avg): self
    {
        $this->avg = $avg;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getAcademicyear(): ?string
    {
        return $this->academicyear;
    }

    public function setAcademicyear(string $academicyear): self
    {
        $this->academicyear = $academicyear;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }
}
