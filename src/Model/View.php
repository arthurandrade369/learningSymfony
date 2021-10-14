<?php

namespace App\Model;

class View
{
    private $body;
    private int $statusCode;
    private string $format;
    private array $header = [];
    private array $groups = [];
    
    public function __construct($body, $statusCode, $header = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->header = $header;
        $this->format = 'json';
    }

    public function getHeader(): array
    {
        return $this->header;
    }

    public function setHeader(array $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }
 
    public function getFormat(): string
    {
        return $this->format;
    }
 
    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function setGroups(array $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    public function getContentType(): string
    {
        switch ($this->format) {
            case 'json':
                return 'application/json';
                break;
            case 'xml':
                return 'application/xml';
                break;
            default:
                return 'text/plain';
                break;
        }
    }
}
