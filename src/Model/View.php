<?php

namespace App\Model;

class View
{
    private array $header = [];
    private $content;
    private int $statusCode;
    private string $address;

    public function getHeader(): array
    {
        return $this->header;
    }

    public function setHeader(array $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }
}
