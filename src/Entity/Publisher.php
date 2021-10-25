<?php

namespace App\Entity;

class Publisher
{
    private int $id;
    private string $name;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $object
     * @return self
     */
    public function setObject($object): self 
    {
        $this->setName($object->getName);

        return $this;
    }
}
