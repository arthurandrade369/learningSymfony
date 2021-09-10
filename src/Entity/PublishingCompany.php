<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PublishingCompanyRepository;
use App\Entity\Book;

/**
 * @ORM\Entity(repositoryClass=PublishingCompanyRepository::class)
 * @ORM\Table(name="publishing_company")
 */
class PublishingCompany
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity="Book", mappedBy="publishing_company_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

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
}
