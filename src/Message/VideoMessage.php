<?php

namespace App\Message;

use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class VideoMessage
{
    private $videoId;


    public function __construct($videoId)
    {
        $this->videoId = $videoId;
    }

    /**
     * Get the value of uniqHash
     */ 
    public function getVideoId()
    {
        return $this->videoId;
    }
}    