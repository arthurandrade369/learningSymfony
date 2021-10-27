<?php

namespace App\Model;

use DateTime;

interface IDateAt
{
    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime;

    /**
     * @param DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTime $createdAt): self;

    /**
     * @return DateTime|null
     */
    public function getModifiedAt(): ?DateTime;

    /**
     * @param DateTime $modifiedAt
     * @return $this
     */
    public function setModifiedAt(DateTime $modifiedAt): self;
}
