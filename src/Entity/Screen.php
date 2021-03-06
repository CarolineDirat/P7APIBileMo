<?php

namespace App\Entity;

use App\Repository\ScreenRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ScreenRepository::class)
 */
class Screen
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
     * @ORM\Column(type="string", length=8, nullable=true, name="bm_size")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=8, description="The size of the phone screen.")
     *
     * @var null|string screen size (diagonal)
     */
    private ?string $size;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, name="bm_technology")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=45, description="The technology of the phone screen.")
     *
     * @var null|string sreen technology
     */
    private $technology;

    /**
     * @ORM\Column(type="string", length=15, nullable=true, name="bm_definition")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=15, description="The definition of the phone screen.")
     *
     * @var null|string screen definition (x*y px)
     */
    private ?string $definition;

    /**
     * @ORM\Column(type="string", length=8, nullable=true, name="bm_resolution")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=8, description="The resolution of the phone screen.")
     *
     * @var null|string screen resolution (ppp)
     */
    private ?string $resolution;

    /**
     * @ORM\Column(type="string", length=7, nullable=true, name="bm_refresh_rate")
     * @Groups({"get_phone"})
     *
     * @OA\Property(type="string", maxLength=7, description="The refresh rate of the phone screen.")
     *
     * @var null|string screen refresh rate (Hz)
     */
    private ?string $refreshRate;

    /**
     * @ORM\OneToOne(targetEntity=Phone::class, inversedBy="screen", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="bm_id", name="phone_bm_id")
     *
     * @var Phone phone corresponding to this screen
     */
    private Phone $phone;

    /**
     * hydrate screen entity.
     *
     * @param string[] $data  keys of $data are corresponding to Screen properties (except $phone)
     * @param Phone    $phone
     *
     * @return self
     */
    public function hydrate(array $data, Phone $phone): self
    {
        $this->setSize($data['screen_size']);
        $this->setTechnology($data['screen_technology']);
        $this->setDefinition($data['screen_definition']);
        $this->setResolution($data['screen_resolution']);
        $this->setRefreshRate($data['screen_refresh_rate']);
        $this->setPhone($phone);

        return $this;
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
     * getSize.
     *
     * @return null|string
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * setSize.
     *
     * @param null|string $size
     *
     * @return self
     */
    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * getTechnology.
     *
     * @return null|string
     */
    public function getTechnology(): ?string
    {
        return $this->technology;
    }

    /**
     * setTechnology.
     *
     * @param null|string $technology
     *
     * @return self
     */
    public function setTechnology(?string $technology): self
    {
        $this->technology = $technology;

        return $this;
    }

    /**
     * getDefinition.
     *
     * @return null|string
     */
    public function getDefinition(): ?string
    {
        return $this->definition;
    }

    /**
     * setDefinition.
     *
     * @param null|string $definition
     *
     * @return self
     */
    public function setDefinition(?string $definition): self
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * getResolution.
     *
     * @return null|string
     */
    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    /**
     * setResolution.
     *
     * @param null|string $resolution
     *
     * @return self
     */
    public function setResolution(?string $resolution): self
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * getRefreshRate.
     *
     * @return null|string
     */
    public function getRefreshRate(): ?string
    {
        return $this->refreshRate;
    }

    /**
     * setRefreshRate.
     *
     * @param null|string $refreshRate
     *
     * @return self
     */
    public function setRefreshRate(?string $refreshRate): self
    {
        $this->refreshRate = $refreshRate;

        return $this;
    }

    /**
     * get Phone corresponding to the screen.
     *
     * @return null|Phone
     */
    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    /**
     * set Phone corresponding to the screen.
     *
     * @param Phone $phone
     *
     * @return self
     */
    public function setPhone(Phone $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
