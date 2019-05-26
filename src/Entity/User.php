<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     * @Assert\Length(min = 8, max = 20, minMessage = "min_lenght", maxMessage = "max_lenght")
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Telefon numarasında sadece sayı bulunmalıdır.")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $create_date;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_access;
    
    private $salt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Basket", mappedBy="user_id", cascade={"persist", "remove"})
     */
    private $basket;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UsersRoles", mappedBy="user_id", orphanRemoval=true)
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    public function getCreateDate(): ?string
    {
        return $this->create_date;
    }

    public function setCreateDate(?string $create_date): self
    {
        $this->create_date = $create_date;

        return $this;
    }
    
    public function getLastAccess(): ?string
    {
        return $this->last_access;
    }

    public function setLastAccess(?string $last_access): self
    {
        $this->last_access = $last_access;

        return $this;
    }
    
    public function getRoles()
    {   
        return [$this->roles[0]->getRoleId()->getName()];
    }
    public function getSalt()
    {
        return $this->salt;
    }
    public function eraseCredentials()
    {
    }

    public function getBasket(): ?Basket
    {
        return $this->basket;
    }

    public function setBasket(Basket $basket): self
    {
        $this->basket = $basket;

        // set the owning side of the relation if necessary
        if ($this !== $basket->getUserId()) {
            $basket->setUserId($this);
        }

        return $this;
    }

    public function addRole(UsersRoles $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->setUserId($this);
        }

        return $this;
    }

    public function removeRole(UsersRoles $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            // set the owning side to null (unless already changed)
            if ($role->getUserId() === $this) {
                $role->setUserId(null);
            }
        }

        return $this;
    }
}
