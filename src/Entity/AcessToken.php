<?php

namespace App\Entity;

use App\Repository\AcessTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AcessTokenRepository::class)
 * @ORM\Table(name="acess_token")
 */
class AcessToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $acessToken;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expirationAt;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $typeToken;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="RefreshToken", inversedBy="id")
     */
    private $refreshToken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcessToken(): ?string
    {
        return $this->acessToken;
    }

    public function setAcessToken(string $acessToken): self
    {
        $this->acessToken = $acessToken;

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
