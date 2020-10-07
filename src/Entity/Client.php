<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client implements UserInterface
{
    /**
     * @var int id
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="bm_id")
     */
    private int $id;

    /**
     * @var string property name that will be the unique "display" name for the user
     * 
     * @ORM\Column(type="string", length=180, unique=true, name="bm_username")
     */
    private string $username;

    /**
     * @var string[] roles
     * 
     * @ORM\Column(type="json", name="bm_roles")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * 
     * @ORM\Column(type="string", name="bm_password")
     */
    private string $password;

    /**
     * @var UuidInterface uuid
     * 
     * @ORM\Column(type="uuid_binary", unique=true, name="bm_uuid")
     */
    private UuidInterface $uuid;

    /**
     * @var string email
     * 
     * @ORM\Column(type="string", length=200, unique=true, name="bm_email")
     */
    private string $email;

    /**
     * @var DateTimeImmutable creation date
     * 
     * @ORM\Column(type="datetime_immutable", name="bm_createdAt")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable updating date
     * 
     * @ORM\Column(type="datetime_immutable", name="bm_updatedAt")
     */
    private DateTimeImmutable $updatedAt;
    
    /**
     * getId
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }
    
    /**
     * setUsername
     *
     * @param  string $username
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * getRoles
     * 
     * @see UserInterface
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    
    /**
     * setRoles
     *
     * @param  string[] $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * getPassword
     * 
     * @see UserInterface
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }
    
    /**
     * setPassword
     *
     * @param  string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * getSalt
     * 
     * Returns the salt that was originally used to encode the password.
     * This can return null if the password was not encoded using a salt.
     * 
     * @see UserInterface
     * @return string|null
     */
    public function getSalt(): ?string
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
     * eraseCredentials.
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     * 
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    /**
     * getUuid
     *
     * @return UuidInterface|null
     */
    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }
    
    /**
     * setUuid
     *
     * @param  UuidInterface $uuid
     * @return self
     */
    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
    
    /**
     * getEmail
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * setEmail
     *
     * @param  string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    /**
     * getCreatedAt
     *
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    /**
     * setCreatedAt
     *
     * @param  DateTimeImmutable $createdAt
     * @return self
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
    /**
     * getUpdatedAt
     *
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    /**
     * setUpdatedAt
     *
     * @param  DateTimeImmutable $updatedAt
     * @return self
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
