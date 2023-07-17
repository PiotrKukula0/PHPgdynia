<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="history")
 */
class History
{
    /**
 * @ORM\Id()
 * @ORM\GeneratedValue()
 * @ORM\Column(type="integer")
 */
private $id;

    /**
 * @ORM\Column(type="integer")
 */
private $firstIn;

/**
 * @ORM\Column(type="integer")
 */
private $secondIn;

/**
 * @ORM\Column(type="integer")
 */
private $firstOut;

/**
 * @ORM\Column(type="integer")
 */
private $secondOut;

/**
 * @ORM\Column(type="datetime")
 * @ORM\JoinColumn(nullable=false)
 */
private $createdAt;

/**
 * @ORM\Column(type="datetime")
 * @ORM\JoinColumn(nullable=false)
 */
private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }


public function __construct()
{
    $this->createdAt = new \DateTime();
    $this->updatedAt = new \DateTime();
}

    /**
     * @return mixed
     */
    public function getFirstIn()
    {
        return $this->firstIn;
    }

    /**
     * @param mixed $firstIn
     */
    public function setFirstIn($firstIn): void
    {
        $this->firstIn = $firstIn;
    }

    /**
     * @return mixed
     */
    public function getSecondIn()
    {
        return $this->secondIn;
    }

    /**
     * @param mixed $secondIn
     */
    public function setSecondIn($secondIn): void
    {
        $this->secondIn = $secondIn;
    }

    /**
     * @return mixed
     */
    public function getFirstOut()
    {
        return $this->firstOut;
    }

    /**
     * @param mixed $firstOut
     */
    public function setFirstOut($firstOut): void
    {
        $this->firstOut = $firstOut;
    }

    /**
     * @return mixed
     */
    public function getSecondOut()
    {
        return $this->secondOut;
    }

    /**
     * @param mixed $secondOut
     */
    public function setSecondOut($secondOut): void
    {
        $this->secondOut = $secondOut;
    }


}
