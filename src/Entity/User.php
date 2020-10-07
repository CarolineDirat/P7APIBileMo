<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="bm_id")
     *
     * @var int id
     */
    private int $id;

    /**
     * @ORM\Column(type="uuid_binary", unique=true, name="bm_uuid")
     *
     * @var UuidInterface uuid
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", length=200, name="bm_email", unique=true)
     *
     * @var string email
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, name="bm_password")
     *
     * @var string password
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=45, name="bm_firstname")
     *
     * @var string firstname
     */
    private string $firstname;

    /**
     * @ORM\Column(type="string", length=45, name="bm_lastname")
     *
     * @var string lastname
     */
    private string $lastname;

    /**
     * @ORM\Column(type="datetime_immutable", name="bm_created_at")
     *
     * @var DateTimeImmutable createdAt
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", name="bm_updated_at")
     *
     * @var DateTimeImmutable updatedAt
     */
    private DateTimeImmutable $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="bm_id")
     *
     * @var Client client corresponding to the User
     */
    private Client $client;

    /**
     * __construct : define $createdAt and $updatedAt.
     */
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * getId.
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getUuid.
     *
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * setUuid.
     *
     * @param UuidInterface $uuid
     *
     * @return self
     */
    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * getEmail.
     *
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * setEmail.
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * getPassword.
     *
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * setPassword.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * getFirstname.
     *
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * setFirstname.
     *
     * @param string $firstname
     *
     * @return self
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * getLastname.
     *
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * setLastname.
     *
     * @param string $lastname
     *
     * @return self
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * getCreatedAt.
     *
     * @return null|DateTimeImmutable
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * setCreatedAt.
     *
     * @param DateTimeImmutable $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * getUpdatedAt.
     *
     * @return null|DateTimeImmutable
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * setUpdatedAt.
     *
     * @param DateTimeImmutable $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
