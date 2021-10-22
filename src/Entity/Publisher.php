<?php

namespace App\Entity;

use App\Repository\PublisherRepository;

class Publisher
{
    private int $id;
    private string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setObject($object)
    {
        $this->setName($object->getName);
    }
}
