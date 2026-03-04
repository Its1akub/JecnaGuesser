<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class GameScore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $playerName;

    #[ORM\Column(type: 'integer')]
    private int $Score;

    #[ORM\Column(type: 'integer')]
    private int $time;

    #[ORM\Column(type: 'string', length: 50)]
    private string $difficulty;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $playedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function setPlayerName(string $playerName): self
    {
        $this->playerName = $playerName;
        return $this;
    }

    public function getScore(): int
    {
        return $this->Score;
    }

    public function setScore(int $score): self
    {
        $this->Score = $score;
        return $this;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;
        return $this;
    }

    public function getDifficulty(): string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    public function getPlayedAt(): DateTimeImmutable
    {
        return $this->playedAt;
    }

    public function setPlayedAt(DateTimeImmutable $playedAt): self
    {
        $this->playedAt = $playedAt;
        return $this;
    }
}
