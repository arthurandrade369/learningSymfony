<?php

namespace App\Model;

class View
{
    private $body;
    private int $statusCode;
    private string $format;
    private array $headers = [];
    private array $groups = [];
    
    /**
     * @param mixed $body
     * @param integer $statusCode
     * @param array $headers
     */
    public function __construct($body, int $statusCode, array $headers = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->format = 'json';
    }

    /**
     * @return array|null
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return self
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     * @return self
     */
    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @param integer $statusCode
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }
 
    /**
     * @return string|null
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }
 
    /**
     * @param string $format
     * @return self
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getGroups(): ?array
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     * @return self
     */
    public function setGroups(array $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContentType(): ?string
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
