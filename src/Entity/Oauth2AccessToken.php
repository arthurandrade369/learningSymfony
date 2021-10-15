<?php

namespace App\Entity;

use App\Repository\OAuth2AccessTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @ORM\Entity(repositoryClass=OAuth2AccessTokenRepository::class)
 * @ORM\Table(name="oauth2_access_token")
 */
class OAuth2AccessToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=64, name="access_token")
     */
    private string $accessToken;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", name="modified_at")
     */
    private \Datetime $modifiedAt;

    /**
     * @ORM\Column(type="datetime", name="expiration_at")
     */
    private \DateTime $expirationAt;

    /**
     * @ORM\Column(type="string", name="type_token", length=32)
     */
    private string $typeToken;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private string $address;

    /**
     * @ORM\OneToOne(targetEntity=OAuth2RefreshToken::class)
     * @ORM\JoinColumn(name="refresh_token_id")
     */
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

    public function getTypeToken(): ?string
    {
        return $this->typeToken;
    }

    public function setTypeToken(string $typeToken): self
    {
        $this->typeToken = $typeToken;

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
