<?php

namespace App\Entity;

use App\Repository\VideoChunkRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use App\Controller\VideoActionController;

/**
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/Video",
 *     collectionOperations={
 *         "post"={
 *             "controller"=VideoActionController::class,
 *             "deserialize"=false,
 *              },
 *          "get"
 *          },
 *     itemOperations={
 *         "get"
 *     }
 * )
 * 
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=VideoChunkRepository::class)
 */
class VideoChunk
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var File|null
     * 
     * @Vich\UploadableField(mapping="video", fileNameProperty="filePath")
     */
    public $file;

    /**
     * @ORM\Column(name="file_path", type="string", length=255, nullable=true)
     */
    private $filePath;

    /**
     * @ORM\ManyToOne(targetEntity=Video::class, inversedBy="videoChunks")
     */
    private $video;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $chunkIndex;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct() {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getVideo(): ?Video
    {
        return $this->video;
    }

    public function setVideo(?Video $video): self
    {
        $this->video = $video;

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

    /**
     * Get the value of chunkIndex
     */ 
    public function getChunkIndex()
    {
        return $this->chunkIndex;
    }

    /**
     * Set the value of chunkIndex
     *
     * @return  self
     */ 
    public function setChunkIndex($chunkIndex)
    {
        $this->chunkIndex = $chunkIndex;

        return $this;
    }

    /**
     * Get the value of filePath
     */ 
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set the value of filePath
     *
     * @return  self
     */ 
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime() {
        // update the modified time
        $this->setUpdatedAt(new \DateTime());
    }
}
