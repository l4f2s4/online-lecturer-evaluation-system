<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
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
    private $cname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cshortname;

    /**
     * @ORM\OneToMany(targetEntity=Department::class, mappedBy="courseassigned")
     */
    private $coursedept;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="assignCourse")
     */
    private $courseStudent;

    /**
     * @ORM\ManyToMany(targetEntity=Program::class, inversedBy="programcourse")
     */
    private $courseProgram;



    public function __construct()
    {
        $this->coursedept = new ArrayCollection();
        $this->courseStudent = new ArrayCollection();
        $this->courseProgram = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCname(): ?string
    {
        return $this->cname;
    }

    public function setCname(string $cname): self
    {
        $this->cname = $cname;

        return $this;
    }

    public function getCshortname(): ?string
    {
        return $this->cshortname;
    }

    public function setCshortname(string $cshortname): self
    {
        $this->cshortname = $cshortname;

        return $this;
    }

    /**
     * @return Collection|Department[]
     */
    public function getCoursedept(): Collection
    {
        return $this->coursedept;
    }

    public function addCoursedept(Department $coursedept): self
    {
        if (!$this->coursedept->contains($coursedept)) {
            $this->coursedept[] = $coursedept;
            $coursedept->setCourseassigned($this);
        }

        return $this;
    }

    public function removeCoursedept(?Department $coursedept): self
    {
        if ($this->coursedept->removeElement($coursedept)) {
            // set the owning side to null (unless already changed)
            if ($coursedept->getCourseassigned() === $this) {
                $coursedept->setCourseassigned(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getCourseStudent(): Collection
    {
        return $this->courseStudent;
    }

    public function addCourseStudent(User $courseStudent): self
    {
        if (!$this->courseStudent->contains($courseStudent)) {
            $this->courseStudent[] = $courseStudent;
            $courseStudent->setAssignCourse($this);
        }

        return $this;
    }

    public function removeCourseStudent(?User $courseStudent): self
    {
        if ($this->courseStudent->removeElement($courseStudent)) {
            // set the owning side to null (unless already changed)
            if ($courseStudent->getAssignCourse() === $this) {
                $courseStudent->setAssignCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Program[]
     */
    public function getCourseProgram(): Collection
    {
        return $this->courseProgram;
    }

    public function addCourseProgram(Program $courseProgram): self
    {
        if (!$this->courseProgram->contains($courseProgram)) {
            $this->courseProgram[] = $courseProgram;
        }

        return $this;
    }

    public function removeCourseProgram(?Program $courseProgram): self
    {
        $this->courseProgram->removeElement($courseProgram);

        return $this;
    }


}
