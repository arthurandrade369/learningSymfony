<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use App\Entity\PublishingCompany;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\Table(name="book")
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantityPages;

    /**
     * @ORM\Column(type="date")
     */
    private $releaseDate;

    /**
     * @ORM\ManyToOne(targetEntity="PublishingCompany", inversedBy="id")
     * @ORM\Column(type="integer")
     */
    private $publishingCompanyId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookTitle(): ?string
    {
        return $this->title;
    }

    public function setBookTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBookAuthor(): ?string
    {
        return $this->author;
    }

    public function setBookAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getQuantityPages(): ?int
    {
        return $this->quantityPages;
    }

    public function setQuantityPages(int $quantityPages): self
    {
        $this->quantityPages = $quantityPages;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getPublishingCompanyId(): ?int
    {
        return $this->publishingCompanyId;
    }

    public function setPublishingCompanyId(int $publishingCompanyId): self
    {
        $this->publishingCompanyId = $publishingCompanyId;

        return $this;
    }

    public function setObject($object): self
    {
        $this->setBookTitle($object->getBookTitle());
        $this->setBookAuthor($object->getBookAuthor());
        $this->setQuantityPages($object->getQuantityPages());
        $this->setReleaseDate($object->getReleaseDate());
        $this->setPublishingCompanyId($object->getPublishingCompanyId());

        return $this;
    }

}
