<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 */
class Phone
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
     * @var DateTimeImmutable date of creation of the phone
     *
     * @ORM\Column(type="datetime_immutable", name="bm_created_at")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable date of updating of the phone
     *
     * @ORM\Column(type="datetime_immutable", name="bm_updated_at")
     */
    private DateTimeImmutable $updatedAt;

    /**
     * @var string constructor of the phone
     *
     * @ORM\Column(type="string", length=55, name="bm_constructor")
     */
    private string $constructor;

    /**
     * @var string name of the phone
     *
     * @ORM\Column(type="string", length=55, name="bm_name")
     */
    private string $name;

    /**
     * @var float price of the phone, in euros
     *
     * @ORM\Column(type="float", name="bm_priceEuro")
     */
    private float $priceEuro;

    /**
     * @var null|string system
     *
     * @ORM\Column(type="string", length=45, nullable=true, name="bm_system")
     */
    private ?string $system;

    /**
     * @var null|string user interface
     *
     * @ORM\Column(type="string", length=45, nullable=true, name="bm_user_interface")
     */
    private ?string $userInterface;

    /**
     * @var null|string processor
     *
     * @ORM\Column(type="string", length=45, nullable=true, name="bm_processor")
     */
    private ?string $processor;

    /**
     * @var null|string RAM
     *
     * @ORM\Column(type="string", length=6, nullable=true, name="bm_ram")
     */
    private ?string $ram;

    /**
     * @var null|string capacity
     *
     * @ORM\Column(type="string", length=10, nullable=true, name="bm_capacity")
     */
    private ?string $capacity;

    /**
     * @var null|string DAS indice
     *
     * @ORM\Column(type="string", length=15, nullable=true, name="bm_das")
     */
    private ?string $das;

    /**
     * @var null|string capacity of the battery
     *
     * @ORM\Column(type="string", length=10, nullable=true, name="bm_battery_capacity")
     */
    private ?string $batteryCapacity;

    /**
     * @var null|bool if the battery can wireless charging
     *
     * @ORM\Column(type="boolean", nullable=true, name="bm_wireless_charging")
     */
    private ?bool $wirelessCharging;

    /**
     * @var null|string weight of the phone
     *
     * @ORM\Column(type="string", length=10, nullable=true, name="bm_weight")
     */
    private ?string $weight;

    /**
     * @var null|UuidInterface
     *
     * @ORM\Column(type="uuid_binary", unique=true, name="bm_uuid")
     */
    private ?UuidInterface $uuid;

    /**
     * @ORM\OneToOne(targetEntity=Size::class, mappedBy="phone", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="bm_id", name="size_bm_id", onDelete="SET NULL")
     *
     * @var null|Size phone dimensions
     */
    private ?Size $size;

    /**
     * @ORM\OneToOne(targetEntity=Screen::class, mappedBy="phone", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="bm_id", name="screen_bm_id", onDelete="SET NULL")
     *
     * @var null|Screen screen corresponding to the phone
     */
    private ?Screen $screen;

    /**
     * __construct : define $createdAt and $updatedAt.
     */
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * hydrate the Phone entity.
     *
     * @param null|string $constructor
     * @param null|string $name
     * @param null|float  $priceEuro
     * @param null|string $system
     * @param null|string $userInterface
     * @param null|string $processor
     * @param null|string $ram
     * @param null|string $capacity
     * @param null|string $das
     * @param null|string $batteryCapacity
     * @param null|bool   $wirelessCharging
     * @param null|string $weight
     * @param Size        $size
     * @param Screen      $screen
     *
     * @return self
     */
    public function hydrate(
        ?string $constructor,
        ?string $name,
        ?float $priceEuro,
        ?string $system,
        ?string $userInterface,
        ?string $processor,
        ?string $ram,
        ?string $capacity,
        ?string $das,
        ?string $batteryCapacity,
        ?bool $wirelessCharging,
        ?string $weight,
        Size $size,
        Screen $screen
    ): self {
        $this->setConstructor($constructor);
        $this->setName($name);
        $this->setPriceEuro($priceEuro);
        $this->setSystem($system);
        $this->setUserInterface($userInterface);
        $this->setProcessor($processor);
        $this->setRam($ram);
        $this->setCapacity($capacity);
        $this->setDas($das);
        $this->setBatteryCapacity($batteryCapacity);
        $this->setWirelessCharging($wirelessCharging);
        $this->setWeight($weight);
        $this->setUuid(Uuid::uuid4());
        $this->setSize($size);
        $this->setScreen($screen);

        return $this;
    }

    /**
     * getId.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getCreatedAt.
     *
     * @return DateTimeImmutable
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

    /**
     * getConstructor.
     *
     * @return null|string
     */
    public function getConstructor(): ?string
    {
        return $this->constructor;
    }

    /**
     * setConstructor.
     *
     * @param string $constructor
     *
     * @return self
     */
    public function setConstructor(string $constructor): self
    {
        $this->constructor = $constructor;

        return $this;
    }

    /**
     * Get name of the phone.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name of the phone.
     *
     * @param string $name name of the phone
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getPriceEuro.
     *
     * @return null|float
     */
    public function getPriceEuro(): ?float
    {
        return $this->priceEuro;
    }

    /**
     * setPriceEuro.
     *
     * @param float $priceEuro
     *
     * @return self
     */
    public function setPriceEuro(float $priceEuro): self
    {
        $this->priceEuro = $priceEuro;

        return $this;
    }

    /**
     * getSystem.
     *
     * @return null|string
     */
    public function getSystem(): ?string
    {
        return $this->system;
    }

    /**
     * setSystem.
     *
     * @param null|string $system
     *
     * @return self
     */
    public function setSystem(?string $system): self
    {
        $this->system = $system;

        return $this;
    }

    /**
     * getUserInterface.
     *
     * @return null|string
     */
    public function getUserInterface(): ?string
    {
        return $this->userInterface;
    }

    /**
     * setUserInterface.
     *
     * @param null|string $userInterface
     *
     * @return self
     */
    public function setUserInterface(?string $userInterface): self
    {
        $this->userInterface = $userInterface;

        return $this;
    }

    /**
     * getProcessor.
     *
     * @return null|string
     */
    public function getProcessor(): ?string
    {
        return $this->processor;
    }

    /**
     * setProcessor.
     *
     * @param null|string $processor
     *
     * @return self
     */
    public function setProcessor(?string $processor): self
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * getRam.
     *
     * @return null|string
     */
    public function getRam(): ?string
    {
        return $this->ram;
    }

    /**
     * setRam.
     *
     * @param null|string $ram
     *
     * @return self
     */
    public function setRam(?string $ram): self
    {
        $this->ram = $ram;

        return $this;
    }

    /**
     * getCapacity.
     *
     * @return null|string
     */
    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    /**
     * setCapacity.
     *
     * @param null|string $capacity
     *
     * @return self
     */
    public function setCapacity(?string $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * getDas.
     *
     * @return null|string
     */
    public function getDas(): ?string
    {
        return $this->das;
    }

    /**
     * setDas.
     *
     * @param null|string $das
     *
     * @return self
     */
    public function setDas(?string $das): self
    {
        $this->das = $das;

        return $this;
    }

    /**
     * getBatteryCapacity.
     *
     * @return null|string
     */
    public function getBatteryCapacity(): ?string
    {
        return $this->batteryCapacity;
    }

    /**
     * setBatteryCapacity.
     *
     * @param null|string $batteryCapacity
     *
     * @return self
     */
    public function setBatteryCapacity(?string $batteryCapacity): self
    {
        $this->batteryCapacity = $batteryCapacity;

        return $this;
    }

    /**
     * getWirelessCharging.
     *
     * @return null|bool
     */
    public function getWirelessCharging(): ?bool
    {
        return $this->wirelessCharging;
    }

    /**
     * setWirelessCharging.
     *
     * @param null|bool $wirelessCharging
     *
     * @return self
     */
    public function setWirelessCharging(?bool $wirelessCharging): self
    {
        $this->wirelessCharging = $wirelessCharging;

        return $this;
    }

    /**
     * getWeight.
     *
     * @return null|string
     */
    public function getWeight(): ?string
    {
        return $this->weight;
    }

    /**
     * setWeight.
     *
     * @param null|string $weight
     *
     * @return self
     */
    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * getUuid.
     *
     * @return null|UuidInterface
     */
    public function getUuid(): ?UuidInterface
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
     * get Size corresponding to the phone.
     *
     * @return null|Size
     */
    public function getSize(): ?Size
    {
        return $this->size;
    }

    /**
     * set Size corresponding to the phone.
     *
     * @param Size $size
     *
     * @return self
     */
    public function setSize(Size $size): self
    {
        $this->size = $size;

        // set the owning side of the relation if necessary
        if ($size->getPhone() !== $this) {
            $size->setPhone($this);
        }

        return $this;
    }

    /**
     * get Screen corresponding to the phone.
     *
     * @return null|Screen
     */
    public function getScreen(): ?Screen
    {
        return $this->screen;
    }

    /**
     * set Screen corresponding to the phone.
     *
     * @param null|Screen $screen
     *
     * @return self
     */
    public function setScreen(?Screen $screen): self
    {
        $this->screen = $screen;

        // set the owning side of the relation if necessary
        if ($screen->getPhone() !== $this) {
            $screen->setPhone($this);
        }

        return $this;
    }
}
