<?php

namespace App\Entity;

use App\Repository\ScreenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScreenRepository::class)
 */
class Screen
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int id
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=8, nullable=true, name="bm_id")
     *
     * @var string|null screen size (diagonal)
     */
    private ?string $size;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, name="bm_technology")
     *
     * @var string|null sreen technology
     */
    private  $technology;

    /**
     * @ORM\Column(type="string", length=15, nullable=true, name="bm_definition")
     *
     * @var string|null screen definition (x*y px)
     */
    private ?string $definition;

    /**
     * @ORM\Column(type="string", length=8, nullable=true, name="bm_resolution")
     *
     * @var string|null screen resolution (ppp)
     */
    private ?string $resolution;

    /**
     * @ORM\Column(type="string", length=7, nullable=true, name="bm_refresh_rate")
     *
     * @var string|null screen refresh rate (Hz)
     */
    private ?string $refreshRate;

    /**
     * @ORM\OneToOne(targetEntity=Phone::class, inversedBy="screen", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Phone phone corresponding to this screen
     */
    private Phone $phone;

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
     * getSize
     *
     * @return string|null
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * setSize
     *
     * @param string|null $size
     * @return self
     */
    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * getTechnology
     *
     * @return string|null
     */
    public function getTechnology(): ?string
    {
        return $this->technology;
    }

    /**
     * setTechnology
     *
     * @param string|null $technology
     * @return self
     */
    public function setTechnology(?string $technology): self
    {
        $this->technology = $technology;

        return $this;
    }

    /**
     * getDefinition
     *
     * @return string|null
     */
    public function getDefinition(): ?string
    {
        return $this->definition;
    }
    
    /**
     * setDefinition
     *
     * @param string|null $definition
     * @return self
     */
    public function setDefinition(?string $definition): self
    {
        $this->definition = $definition;

        return $this;
    }
    
    /**
     * getResolution
     *
     * @return string|null
     */
    public function getResolution(): ?string
    {
        return $this->resolution;
    }
    
    /**
     * setResolution
     *
     * @param  string|null $resolution
     * @return self
     */
    public function setResolution(?string $resolution): self
    {
        $this->resolution = $resolution;

        return $this;
    }
    
    /**
     * getRefreshRate
     *
     * @return string|null
     */
    public function getRefreshRate(): ?string
    {
        return $this->refreshRate;
    }
    
    /**
     * setRefreshRate
     *
     * @param  string|null $refreshRate
     * @return self
     */
    public function setRefreshRate(?string $refreshRate): self
    {
        $this->refreshRate = $refreshRate;

        return $this;
    }
    
    /**
     * get Phone corresponding to the screen
     *
     * @return Phone|null
     */
    public function getPhone(): ?Phone
    {
        return $this->phone;
    }
    
    /**
     * set Phone corresponding to the screen
     *
     * @param Phone $phone
     * @return self
     */
    public function setPhone(Phone $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
