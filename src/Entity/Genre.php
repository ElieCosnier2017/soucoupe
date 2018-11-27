<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 */
class Genre
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

    /**
     * @return mixed
     */
    public function getTypemedia()
    {
        return $this->typemedia;
    }

    /**
     * @param mixed $typemedia
     */
    public function setTypemedia($typemedia)
    {
        $this->typemedia = $typemedia;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeMedia", inversedBy="genre")
     */
    private $typemedia;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Media", mappedBy="genre")
     */
    private $media;

    public function __toString()
    {
        return $this->name;
    }
}
