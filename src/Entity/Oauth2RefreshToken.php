<?php

namespace App\Entity;

use App\Entity\Account;
use App\Repository\OAuth2RefreshTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OAuth2RefreshTokenRepository::class)
 * @ORM\Table(name="oauth2_refresh_token")
 */
class OAuth2RefreshToken
{
    public const TTL = 3600;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=64, name="refresh_token")
     */
    private string $refreshToken;

    /**
     * @ORM\Column(type="datetimetz", name="created_at")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetimetz", name="modified_at")
     */
    private \DateTime $modifiedAt;

    /**
     * @ORM\OneToOne(targetEntity=Account::class)
     * @ORM\JoinColumn(name="account_id")
     */
    private Account $account;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTime
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTime $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getExpirationAt(): ?\DateTimeInterface
    {
        return $this->expirationAt;
    }

    public function setExpirationAt(\DateTimeInterface $expirationAt): self
    {
        $this->expirationAt = $expirationAt;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
