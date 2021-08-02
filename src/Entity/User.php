<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $regno;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Username;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nationality;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $maritalStatus;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phoneno;
       /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $yos;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ResetToken;

     /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $userimage;
      /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="courseStudent")
     */
    private $assignCourse;

    /**
     * @ORM\ManyToMany(targetEntity=Program::class, mappedBy="teacherAssigned")
     */
    private $programassigned;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="assigndept")
     */
    private $userdept;

    /**
     * @ORM\OneToMany(targetEntity=EvaluateMark::class, mappedBy="studentMark")
     */
    private $addMark;

    public function __construct()
    {
        $this->addMark = new ArrayCollection();
        $this->programassigned = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->Username;
    }

   /** Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */

    public function getRoles()
    {
      //  $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
       // $roles[] = 'ROLE_USER';

        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->maritalStatus;
    }

    public function setMaritalStatus(string $maritalStatus): self
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }


     public function getResetToken(): ?string
    {
        return $this->ResetToken;
    }

    public function setResetToken(?string $ResetToken): self
    {
        $this->ResetToken = $ResetToken;

        return $this;
    }


         public function getUserimage()
    {
        return $this->userimage;
    }

    public function setUserimage($userimage)
    {
        $this->userimage = $userimage;

        return $this;
    }
    public function getPhoneno(): ?int
    {
        return $this->phoneno;
    }

    public function setPhoneno(?int $phoneno): self
    {
        $this->phoneno = $phoneno;

        return $this;
    }

    /**
     * @return Collection|Program[]
     */
    public function getProgramassigned(): Collection
    {
        return $this->programassigned;
    }

    public function addProgramassigned(Program $programassigned): self
    {
        if (!$this->programassigned->contains($programassigned)) {
            $this->programassigned[] = $programassigned;
            $programassigned->addTeacherAssigned($this);
        }

        return $this;
    }

    public function removeProgramassigned(?Program $programassigned): self
    {
        if ($this->programassigned->removeElement($programassigned)) {
            $programassigned->removeTeacherAssigned($this);
        }

        return $this;
    }

    public function getUserdept(): ?Department
    {
        return $this->userdept;
    }

    public function setUserdept(?Department $userdept): self
    {
        $this->userdept = $userdept;

        return $this;
    }
     public function getStudentevaluate(): ?Evaluation
    {
        return $this->studentevaluate;
    }

    public function setStudentevaluate(?Evaluation $studentevaluate): self
    {
        $this->studentevaluate = $studentevaluate;

        return $this;
    }
       public function getAssignCourse(): ?Course
    {
        return $this->assignCourse;
    }
     public function getCourse(): ?int
    {
        return $this->assignCourse;
    }
    public function setAssignCourse(?Course $assignCourse): self
    {
        $this->assignCourse = $assignCourse;

        return $this;
    }
   /**
     * @return Collection|EvaluateMark[]
     */
    public function getAddMark(): Collection
    {
        return $this->addMark;
    }

    public function addAddMark(EvaluateMark $addMark): self
    {
        if (!$this->addMark->contains($addMark)) {
            $this->addMark[] = $addMark;
            $addMark->setStudentMark($this);
        }

        return $this;
    }

    public function removeAddMark(?EvaluateMark $addMark): self
    {
        if ($this->addMark->removeElement($addMark)) {
            // set the owning side to null (unless already changed)
            if ($addMark->getStudentMark() === $this) {
                $addMark->setStudentMark(null);
            }
        }

        return $this;
    }
   public function getYos(): ?int
    {
        return $this->yos;
    }

    public function setYos(int $yos): self
    {
        $this->yos = $yos;

        return $this;
    }
     public function getRegno(): ?string
    {
        return $this->regno;
    }

    public function setRegno(string $regno): self
    {
        $this->regno = $regno;

        return $this;
    }
}
