<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgramRepository::class)
 */
class Program
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
    private $pname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pshort;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pcode;
    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="programassigned")
     */
    private $teacherAssigned;

    /**
     * @ORM\Column(type="integer")
     */
    private $pcredit;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, mappedBy="courseProgram")
     */
    private $programcourse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $semester;



    public function __construct()
    {

        $this->teacherAssigned = new ArrayCollection();
        $this->programcourse = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPname(): ?string
    {
        return $this->pname;
    }

    public function setPname(string $pname): self
    {
        $this->pname = $pname;

        return $this;
    }

    public function getPshort(): ?string
    {
        return $this->pshort;
    }

    public function setPshort(string $pshort): self
    {
        $this->pshort = $pshort;

        return $this;
    }

    public function getPcode(): ?string
    {
        return $this->pcode;
    }

    public function setPcode(string $pcode): self
    {
        $this->pcode = $pcode;

        return $this;
    }


    /**
     * @return Collection|User[]
     */
    public function getTeacherAssigned(): Collection
    {
        return $this->teacherAssigned;
    }

    public function addTeacherAssigned(User $teacherAssigned): self
    {
        if (!$this->teacherAssigned->contains($teacherAssigned)) {
            $this->teacherAssigned[] = $teacherAssigned;
        }

        return $this;
    }

    public function removeTeacherAssigned(User $teacherAssigned): self
    {
        $this->teacherAssigned->removeElement($teacherAssigned);

        return $this;
    }

    public function getPcredit(): ?string
    {
        return $this->pcredit;
    }

    public function setPcredit(string $pcredit): self
    {
        $this->pcredit = $pcredit;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getProgramcourse(): Collection
    {
        return $this->programcourse;
    }

    public function addProgramcourse(Course $programcourse): self
    {
        if (!$this->programcourse->contains($programcourse)) {
            $this->programcourse[] = $programcourse;
            $programcourse->addCourseProgram($this);
        }

        return $this;
    }

    public function removeProgramcourse(Course $programcourse): self
    {
        if ($this->programcourse->removeElement($programcourse)) {
            $programcourse->removeCourseProgram($this);
        }

        return $this;
    }

    public function getSemester(): ?string
    {
        return $this->semester;
    }

    public function setSemester(string $semester): self
    {
        $this->semester = $semester;

        return $this;
    }


}
