<?php

namespace App\Model;

class OAuth2Request
{
    public const GRANT_TYPE_REFRESH_TOKEN = 'refreshToken';
    public const GRANT_TYPE_PASSWORD = 'password';
    /**
     * @Type("string")
     * @var string
     */
    private string $username;

    /**
     * @Type("string")
     * @var string
     */
    private string $password;

    /**
     * @Type("string")
     * @var string
     */
    private string $grantType;

    /**
     * Get the value of username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of grantType
     */
    public function getGrantType(): string
    {
        return $this->grantType;
    }

    /**
     * Set the value of grantType
     *
     * @return  self
     */
    public function setGrantType(string $grantType): self
    {
        $this->grantType = $grantType;

        return $this;
    }
}
