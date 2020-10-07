<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SizeRepository::class)
 */
class Size
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
     * @ORM\Column(type="string", length=10, name="bm_width")
     *
     * @var null|string
     */
    private ?string $width;

    /**
     * @ORM\Column(type="string", length=10, name="bm_height")
     *
     * @var null|string height
     */
    private ?string $height;

    /**
     * @ORM\Column(type="string", length=10, name="bm_thickness")
     *
     * @var null|string thickness
     */
    private ?string $thickness;

    /**
     * @ORM\OneToOne(targetEntity=Phone::class, inversedBy="size", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="bm_id", name="phone_bm_id")
     *
     * @var Phone phone corresponding to the size
     */
    private Phone $phone;
    
    /**
     * hydrate size entity
     *
     * @param string  $width
     * @param string  $height
     * @param null|string  $thickness
     * @param Phone $phone
     * 
     * @return self
     */
    public function hydrate(?string $width, ?string $height, ?string $thickness, Phone $phone): self
    {
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setThickness($thickness);
        $this->setPhone($phone);

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
     * getWidth.
     *
     * @return string
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }

    /**
     * setWidth.
     *
     * @param string $width
     *
     * @return self
     */
    public function setWidth(?string $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * getHeight.
     *
     * @return null|string
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * setHeight.
     *
     * @param null|string $height
     *
     * @return self
     */
    public function setHeight(?string $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * getThickness.
     *
     * @return null|string
     */
    public function getThickness(): ?string
    {
        return $this->thickness;
    }

    /**
     * setThickness.
     *
     * @param null|string $thickness
     *
     * @return self
     */
    public function setThickness(?string $thickness): self
    {
        $this->thickness = $thickness;

        return $this;
    }

    /**
     * Get phone corresponding to the size.
     *
     * @return Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone corresponding to the size.
     *
     * @param Phone $phone phone corresponding to the size
     *
     * @return self
     */
    public function setPhone(Phone $phone)
    {
        $this->phone = $phone;

        return $this;
    }
}
