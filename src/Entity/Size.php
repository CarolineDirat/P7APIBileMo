<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SizeRepository::class)
 * @ORM\Table(name="sizes")
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
     * @var null|string thikness
     */
    private ?string $thikness;

    /**
     * @ORM\OneToOne(targetEntity=Phone::class, inversedBy="size", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="bm_id")
     *
     * @var Phone phone corresponding to the size
     */
    private Phone $phone;

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
     * getThikness.
     *
     * @return null|string
     */
    public function getThikness(): ?string
    {
        return $this->thikness;
    }

    /**
     * setThikness.
     *
     * @param null|string $thikness
     *
     * @return self
     */
    public function setThikness(?string $thikness): self
    {
        $this->thikness = $thikness;

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
