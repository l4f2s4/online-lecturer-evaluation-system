<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
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
    private $name;
    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="coursedept")
     */
    private $courseassigned;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="userdept")
     */
    private $assigndept;

    public function __construct()
    {
        $this->assigndept = new ArrayCollection();
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
    public function getCourseassigned(): ?Course
    {
        return $this->courseassigned;
    }

    public function setCourseassigned(?Course $courseassigned): self
    {
        $this->courseassigned = $courseassigned;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAssigndept(): Collection
    {
        return $this->assigndept;
    }

    public function addAssigndept(User $assigndept): self
    {
        if (!$this->assigndept->contains($assigndept)) {
            $this->assigndept[] = $assigndept;
            $assigndept->setUserdept($this);
        }

        return $this;
    }

    public function removeAssigndept(?User $assigndept): self
    {
        if ($this->assigndept->removeElement($assigndept)) {
            // set the owning side to null (unless already changed)
            if ($assigndept->getUserdept() === $this) {
                $assigndept->setUserdept(null);
            }
        }

        return $this;
    }
}
