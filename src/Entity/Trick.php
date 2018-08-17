<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @ORM\EntityListeners({"App\EventListener\TrickSubscriber"})
 * @UniqueEntity(fields="name", message="Trick déjà enregistré.")
 */
class Trick
{

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", cascade={"persist"}, mappedBy="trick")
     */
    protected $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture",cascade={"persist"}, mappedBy="trick")
     * @Assert\All({
     *      @Assert\File(
     *     maxSize = "30000k",
     *     mimeTypes={ "image/png", "image/jpeg" })
     *})
     */
    protected $pictures;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Family")
     * @ORM\JoinColumn(nullable=false)
     */
    private $family;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Picture")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $listingPicture;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Le nom  ne doit pas être vide.")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description ne doit pas être vide.")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $slug;

    public function __construct()
    {
        $this->videos   = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function addVideo(Video $video)
    {
        $video->setTrick($this);
        $this->videos->add($video);
    }

    public function removeVideo(Video $video)
    {
        $this->videos->removeElement($video);
    }

    public function addAPicture(Picture $picture)
    {
        $picture->setTrick($this);
        $this->pictures->add($picture);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFamily(): ?Family
    {
        return $this->family;
    }


    public function setFamily(?Family $family):self
    {
        $this->family = $family;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getListingPicture(): Picture
    {
        return $this->listingPicture ?? new Picture();
    }

    /**
     * @param $listingPicture
     * @return Trick
     */
    public function setListingPicture($listingPicture): self
    {
        $this->listingPicture = $listingPicture;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     */
    public function getVideos()
    {
        return $this->videos;
    }

    public function setVideos(Video $video = null)
    {
        $this->videos = $video;
    }

    /**
     * @return mixed
     */
    public function getPictures()
    {
        return $this->pictures;
    }


    public function setPictures($pictures)
    {
        $this->pictures = $pictures;
    }
}
