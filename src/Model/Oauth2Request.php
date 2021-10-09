<?php

namespace App\Model;

class OAuth2Request
{
    public const GRANT_TYPE_REFRESH_TOKEN = 'refreshToken';
    public const GRANT_TYPE_PASSWORD = 'password';

    private string $username;
    private string $password;
    private string $grantType;

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return  self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return  self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGrantType(): ?string
    {
        return $this->grantType;
    }

    /**
     * @return  self
     */
    public function setGrantType(string $grantType): self
    {
        $this->grantType = $grantType;

        return $this;
    }
}
