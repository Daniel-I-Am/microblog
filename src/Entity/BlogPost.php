<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostRepository")
 */
class BlogPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $TimeStamp;
    /**
     * @ORM\Column(type="text")
     */
    private $Content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $LastEdited;

    public function getId()
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getTimeStamp(): ?\DateTimeInterface
    {
        return $this->TimeStamp;
    }

    public function setTimeStamp(\DateTimeInterface $TimeStamp): self
    {
        $this->TimeStamp = $TimeStamp;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getLastEdited(): ?\DateTimeInterface
    {
        return $this->LastEdited;
    }

    public function setLastEdited(?\DateTimeInterface $LastEdited): self
    {
        $this->LastEdited = $LastEdited;

        return $this;
    }
}
