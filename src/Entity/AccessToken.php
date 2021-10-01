<?php

namespace App\Entity;

use App\Repository\AccessTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccessTokenRepository::class)
 * @ORM\Table(name="access_token")
 */
class AccessToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $accessToken;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private \Datetime $modifiedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $expirationAt;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private string $typeToken;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="RefreshToken", inversedBy="id")
     */
    private $refreshToken;

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

    public function getRefreshToken(): ?int
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(int $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}
