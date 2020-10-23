<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @var null|UuidInterface
     *
     * @ORM\Column(type="uuid_binary", unique=true, name="bm_uuid")
     * @Groups({"collection", "get_phone"})
     *
     * @OA\Property(type="string", maxLength=36, minLength=36, description="The unique identifier of the phone.")
     */
    private ?UuidInterface $uuid;

    /**
     * @var DateTimeImmutable date of creation of the phone
     *
     * @ORM\Column(type="datetime_immutable", name="bm_created_at")
     * @Groups({"get_phone"})
     *
     * @OA\Property(description="The date of creation of the phone.")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable date of updating of the phone
     *
     * @ORM\Column(type="datetime_immutable", name="bm_updated_at")
     * @Groups({"get_phone"})
     *
     * @OA\Property(description="The date of updating of the phone.")
     */
    private DateTimeImmutable $updatedAt;

    /**
     * @var string constructor of the phone
     *
     * @ORM\Column(type="string", length=55, name="bm_constructor")
     * @Groups({"collection", "get_phone"})
     *
     * @OA\Property(type="string", maxLength=55, description="The name of the phone constructor.")
     */
    private string $constructor;

    /**
     * @var string name of the phone
     *
     * @ORM\Column(type="string", length=55, name="bm_name")
     * @Groups({"collection", "get_phone"})
     *
     * @OA\Property(type="string", maxLength=55, description="The name of the phone.")
     */
    private string $name;

    /**
     * @var float price of the phone, in euros
     *
     * @ORM\Column(type="float", name="bm_priceEuro")
     * @Groups({"collection", "get_phone"})
     *
     * @OA\Property(type="number", description="The phone price.")
     */
    private float $priceEuro;

    /**
     * @var null|string system
     *
     * @ORM\Column(type="string", length=45, nullable=true, name="bm_system")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=45, description="The operating system of the phone.")
     */
    private ?string $system;

    /**
     * @var null|string user interface
     *
     * @ORM\Column(type="string", length=45, nullable=true, name="bm_user_interface")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=45, description="The user interface of the phone.")
     */
    private ?string $userInterface;

    /**
     * @var null|string processor
     *
     * @ORM\Column(type="string", length=45, nullable=true, name="bm_processor")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=45, description="The processor of the phone.")
     */
    private ?string $processor;

    /**
     * @var null|string RAM
     *
     * @ORM\Column(type="string", length=6, nullable=true, name="bm_ram")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=6, description="The RAM of the phone.")
     */
    private ?string $ram;

    /**
     * @var null|string capacity
     *
     * @ORM\Column(type="string", length=10, nullable=true, name="bm_capacity")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=10, description="The phone storage capacity.")
     */
    private ?string $capacity;

    /**
     * @var null|string DAS indice
     *
     * @ORM\Column(type="string", length=15, nullable=true, name="bm_das")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=15, description="The specific absorption rate of the phone.")
     */
    private ?string $das;

    /**
     * @var null|string capacity of the battery
     *
     * @ORM\Column(type="string", length=10, nullable=true, name="bm_battery_capacity")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=10, description="The battery capacity of the phone.")
     */
    private ?string $batteryCapacity;

    /**
     * @var null|bool if the battery can wireless charging
     *
     * @ORM\Column(type="boolean", nullable=true, name="bm_wireless_charging")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", description="Whether the phone can be charged wirelessly.")
     */
    private ?bool $wirelessCharging;

    /**
     * @var null|string weight of the phone
     *
     * @ORM\Column(type="string", length=10, nullable=true, name="bm_weight")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=10, description="The weight of the phone.")
     */
    private ?string $weight;

    /**
     * @ORM\OneToOne(targetEntity=Size::class, mappedBy="phone", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="bm_id", name="size_bm_id", onDelete="SET NULL")
     * @Groups({"get_phone"})
     *
     * @OA\Property(ref=@Model(type=Size::class), description="Phone size informations.")
     *
     * @var null|Size phone dimensions
     */
    private ?Size $size;

    /**
     * @ORM\OneToOne(targetEntity=Screen::class, mappedBy="phone", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="bm_id", name="screen_bm_id", onDelete="SET NULL")
     * @Groups({"get_phone"})
     *
     * @OA\Property(ref=@Model(type=Screen::class), description="Phone screen informations.")
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
        $this->uuid = Uuid::uuid4();
    }
    
    /**
     * computeEtag
     *
     * @return string
     */
    public function computeEtag(): string
    {
        return md5($this->getUuid().$this->getUpdatedAt()->getTimestamp());
    }

    /**
     * hydrate the Phone entity.
     *
     * @param array<string, mixed> $data   keys of $data are corresponding
     *                                     to Phone properties (except $uuid, $size and $screen)
     * @param Size                 $size
     * @param Screen               $screen
     *
     * @return self
     */
    public function hydrate(array $data, Size $size, Screen $screen): self
    {
        $this->setConstructor($data['constructor']);
        $this->setName($data['name']);
        $this->setPriceEuro($data['price_euro']);
        $this->setSystem($data['system']);
        $this->setUserInterface($data['user_interface']);
        $this->setProcessor($data['processor']);
        $this->setRam($data['ram']);
        $this->setCapacity($data['capacity']);
        $this->setDas($data['das']);
        $this->setBatteryCapacity($data['battery_capacity']);
        $this->setWirelessCharging($data['wireless_charging']);
        $this->setWeight($data['weight']);
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
