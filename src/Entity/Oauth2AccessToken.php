<?php

namespace App\Entity;

use App\Entity\OAuth2RefreshToken;
use DateTime;
use DateTimeInterface;

class OAuth2AccessToken
{
    private int $id;
    private string $accessToken;
    private DateTime $createdAt;
    private Datetime $modifiedAt;
    private DateTime $expirationAt;
    private string $tokenType;
    private string $address;
    private OAuth2RefreshToken $refreshToken;

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return self
     */
    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return self
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getModifiedAt(): ?DateTimeInterface
    {
        return $this->modifiedAt;
    }

    /**
     * @param DateTimeInterface $modifiedAt
     * @return self
     */
    public function setModifiedAt(DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getExpirationAt(): ?DateTimeInterface
    {
        return $this->expirationAt;
    }

    /**
     * @param DateTimeInterface $expirationAt
     * @return self
     */
    public function setExpirationAt(DateTimeInterface $expirationAt): self
    {
        $this->expirationAt = $expirationAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    /**
     * @param string $tokenType
     * @return self
     */
    public function setTokenType(string $tokenType): self
    {
        $this->tokenType = $tokenType;

        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return self
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return OAuth2RefreshToken|null
     */
    public function getRefreshToken(): ?OAuth2RefreshToken
    {
        return $this->refreshToken;
    }

    /**
     * @param OAuth2RefreshToken $refreshToken
     * @return self
     */
    public function setRefreshToken(OAuth2RefreshToken $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}
