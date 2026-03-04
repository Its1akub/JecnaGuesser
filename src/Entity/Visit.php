<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitRepository::class)]
class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $utmSource = null;

    #[ORM\Column(length: 100)]
    private ?string $utmMedium = null;

    #[ORM\Column(length: 100)]
    private ?string $utmCampaign = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $visitedAt = null;

    #[ORM\Column]
    private ?string $SessionId = null;

    public function getSessionId(): ?string
    {
        return $this->SessionId;
    }

    public function setSessionId(?string $SessionId): void
    {
        $this->SessionId = $SessionId;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtmSource(): ?string
    {
        return $this->utmSource;
    }

    public function setUtmSource(string $utmSource): static
    {
        $this->utmSource = $utmSource;

        return $this;
    }

    public function getUtmMedium(): ?string
    {
        return $this->utmMedium;
    }

    public function setUtmMedium(string $utmMedium): static
    {
        $this->utmMedium = $utmMedium;

        return $this;
    }

    public function getUtmCampaign(): ?string
    {
        return $this->utmCampaign;
    }

    public function setUtmCampaign(string $utmCampaign): static
    {
        $this->utmCampaign = $utmCampaign;

        return $this;
    }

    public function getVisitedAt(): ?\DateTimeImmutable
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeImmutable $visitedAt): static
    {
        $this->visitedAt = $visitedAt;

        return $this;
    }
}
