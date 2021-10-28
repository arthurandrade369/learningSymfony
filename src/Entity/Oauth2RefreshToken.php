<?php

namespace App\Entity;

use App\Entity\Account;
use App\Model\IDateAt;
use DateTime;
use DateTimeInterface;

class OAuth2RefreshToken implements IDateAt
{
    public const TTL = 3600;

    private int $id;
    private string $refreshToken;
    private DateTime $createdAt;
    private DateTime $modifiedAt;
    private Account $account;

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
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     * @return self
     */
    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

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
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return self
     */
    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
