<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $info;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $queue;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $step;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dept;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }

    public function setQueue(string $queue): self
    {
        $this->queue = $queue;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(?int $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getDept(): ?string
    {
        return $this->dept;
    }

    public function setDept(string $dept): self
    {
        $this->dept = $dept;

        return $this;
    }
}
