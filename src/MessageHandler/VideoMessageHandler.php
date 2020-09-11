<?php


namespace App\MessageHandler;

use App\Entity\Video;
use App\Entity\VideoChunk;
use App\Message\VideoMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class VideoMessageHandler implements MessageHandlerInterface
{

    public $em;
    public $params;

    public function __construct(EntityManagerInterface $entityManagerInterface, ParameterBagInterface $params)
    {
        $this->em = $entityManagerInterface;
        $this->params = $params;
    }

    public function __invoke(VideoMessage $videoMessage)
    {
        $videoId = $videoMessage->getVideoId();
        $em = $this->em;

        $video = $em->getRepository(Video::class)->find($videoId);

        $chunkDir = $this->params->get('chunk_dir');
        $videoDir = $this->params->get('video_dir');
        $thumbnailDir = $this->params->get('thumbnail_dir');

        $chunkArr = [];

        foreach($video->getVideoChunks() as $chunk) {
            $chunkArr[] = $chunkDir.$chunk->getFilePath();
        }

        $mainVideoName = $video->getVideoHash().".mp4";
        $mainVideoPath = $videoDir.$mainVideoName;
        $thumbnailName = $video->getVideoHash().".jpg";

        $ffmpeg = FFMpeg::create();
        $videoOperation = $ffmpeg->open($chunkArr[0]); 
        $videoOperation
            ->concat($chunkArr)
            ->saveFromSameCodecs($mainVideoPath, true);
            
        $videoChunks = $em->getRepository(VideoChunk::class)->findBy(array(
            "status" => 0,
            "video" => $video
        ));
        
        foreach($videoChunks as $chunk) {
            $chunk->setStatus(1);

            $em->flush();
        }
        
        $videoDuration = $this->captureThumbnail($mainVideoPath, $thumbnailDir.$thumbnailName);
        $video->setThumbnail($thumbnailName);
        $video->setVideoSecond($videoDuration);
        $video->setPath($mainVideoName);
        $video->setStatus(1);
        $video->setLastChunk(end($chunkArr));

        $em->flush();
        
        echo "Video with hash => ". $video->getVideoHash(). "is done";
    }

    public function captureThumbnail($videoPath, $to) {
        $ffprobe = FFProbe::create();
        $duration = $ffprobe->format($videoPath)->get('duration');

        $ffmpeg = FFMpeg::create();
        $capture = $ffmpeg->open($videoPath);
        if($duration > 10) {
            $snapShotAt = 5;
        } else {
            $snapShotAt = 1;
        }
        $capture->frame(TimeCode::fromSeconds($snapShotAt))->save($to);

        return $duration;
    }
    
}    