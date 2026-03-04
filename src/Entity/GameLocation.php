<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class GameLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;


    #[ORM\Column(type: 'float')]
    private float $x;

    #[ORM\Column(type: 'float')]
    private float $y;

    #[ORM\Column(type: 'integer')]
    private int $floor;

    #[ORM\Column(type: 'string', length: 255)]
    private string $imagePath;

    #[ORM\Column(type: 'string', length: 255)]
    private string $difficulty;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getX(): float
    {
        return $this->x;
    }

    public function setX(float $x): self
    {
        $this->x = $x;
        return $this;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function setY(float $y): self
    {
        $this->y = $y;
        return $this;
    }

    public function getFloor(): int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;
        return $this;
    }

    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function getDifficulty(): string
    {
        return $this->difficulty;
    }

}
