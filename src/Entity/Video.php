<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use App\Controller\VideoDeletedController;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "get"
 *          },
 *     itemOperations={
 *         "get",
 *         "put",
 *         "delete"={
 *             "method"="DELETE",
 *             "path"="/deleteVideo/{id}",
 *             "controller"=VideoDeletedController::class  
 *          }
 *      }
 *     )
 * @ORM\Entity()
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastChunk;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $videoSecond;
    
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $videoHash;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $remainingChunk;

    /**
     * @ORM\OneToMany(targetEntity=VideoChunk::class, mappedBy="video", cascade={"remove"})
     */
    private $videoChunks;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->videoChunks = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getVideoSecond(): ?int
    {
        return $this->videoSecond;
    }

    public function setVideoSecond(?int $videoSecond): self
    {
        $this->videoSecond = $videoSecond;

        return $this;
    }

    
    public function getVideoHash(): ?string
    {
        return $this->videoHash;
    }

    public function setVideoHash(string $videoHash): self
    {
        $this->videoHash = $videoHash;

        return $this;
    }

    /**
     * @return Collection|VideoChunk[]
     */
    public function getVideoChunks(): Collection
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("status", 0))
            ->orderBy(['chunkIndex' => 'ASC']);

            return $this->videoChunks->matching($criteria);
    }

    public function addVideoChunk(VideoChunk $videoChunk): self
    {
        if (!$this->videoChunks->contains($videoChunk)) {
            $this->videoChunks[] = $videoChunk;
            $videoChunk->setVideo($this);
        }

        return $this;
    }

    public function removeVideoChunk(VideoChunk $videoChunk): self
    {
        if ($this->videoChunks->contains($videoChunk)) {
            $this->videoChunks->removeElement($videoChunk);
            // set the owning side to null (unless already changed)
            if ($videoChunk->getVideo() === $this) {
                $videoChunk->setVideo(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of lastChunk
     */ 
    public function getLastChunk()
    {
        return $this->lastChunk;
    }

    /**
     * Set the value of lastChunk
     *
     * @return  self
     */ 
    public function setLastChunk($lastChunk)
    {
        $this->lastChunk = $lastChunk;

        return $this;
    }

    /**
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of remainingChunk
     */ 
    public function getRemainingChunk()
    {
        return $this->remainingChunk;
    }

    /**
     * Set the value of remainingChunk
     *
     * @return  self
     */ 
    public function setRemainingChunk($remainingChunk)
    {
        $this->remainingChunk = $remainingChunk;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));    
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
