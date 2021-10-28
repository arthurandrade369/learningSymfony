<?php

namespace App\Model;

use DateTimeInterface;

interface IDateAt
{
    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface;

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self;

    /**
     * @return DateTimeInterface|null
     */
    public function getModifiedAt(): ?DateTimeInterface;

    /**
     * @param DateTimeInterface $modifiedAt
     * @return $this
     */
    public function setModifiedAt(DateTimeInterface $modifiedAt): self;
}
