<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Account implements UserInterface
{
    private int $id;
    private string $name;
    private string $email;
    private string $scope;
    private string $password;
    private ?string $plainPassword = null;
    private string $type;
    private string $enabled;
    private DateTime $createdAt;
    private Datetime $modifiedAt;

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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $scopes = explode(':', $this->scope);

        $roles = $scopes;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     * @return self
     */
    public function setScope(string $scope): self
    {
        $this->scope = $scope;

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
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return ("thisissalt?");
    }

    /**
     * @return void
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return boolean|null
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     * @return self
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

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
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return self
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
