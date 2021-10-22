<?php

namespace App\Entity;

use App\Repository\OAuth2AccessTokenRepository;
use App\Entity\OAuth2RefreshToken;

class OAuth2AccessToken
{
    private int $id;
    private string $accessToken;
    private \DateTime $createdAt;
    private \Datetime $modifiedAt;
    private \DateTime $expirationAt;
    private string $tokenType;
    private string $address;
    private OAuth2RefreshToken $refreshToken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

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

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeInterface $modifiedAt): self
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

    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    public function setTokenType(string $tokenType): self
    {
        $this->tokenType = $tokenType;

        return $this;
    }
    
    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getRefreshToken(): ?OAuth2RefreshToken
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(OAuth2RefreshToken $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}
