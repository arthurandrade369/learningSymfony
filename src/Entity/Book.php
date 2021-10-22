<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use App\Entity\Publisher;

class Book
{
    private int $id;
    private string $title;
    private string $author;
    private int $quantityPages;
    private int $releaseDate;
    private Publisher $publisher;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
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

    public function getReleaseDate(): ?int
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(int $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(Publisher $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function setObject($object): self
    {
        $this->setTitle($object->getTitle());
        $this->setAuthor($object->getAuthor());
        $this->setQuantityPages($object->getQuantityPages());
        $this->setReleaseDate($object->getReleaseDate());

        return $this;
    }
}
