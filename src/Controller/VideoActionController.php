<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\VideoChunk;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\VideoMessage;
use App\Services\RequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

final class VideoActionController extends AbstractController
{
    public $uniqHash;
    public $uploadedFile;
    public $chunkIndex;
    public $chunkTotal;
    public $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->uploadedFile = $this->request->files->get('file');
        $this->uniqHash = $this->request->get('uniqHash');
        $this->chunkIndex = $this->request->get('chunkIndex');
        $this->chunkTotal = $this->request->get('totalChunk');
    }

    public function __invoke(MessageBusInterface $bus)
    {   
        if (!$this->uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $em = $this->getDoctrine()->getManager();

        $video = $em->getRepository(Video::class)->findOneBy(array(
            "videoHash" => $this->uniqHash
        ));

        if(!$video) {
            $video = new Video();
            
            $video->setName($this->uploadedFile->getClientOriginalName());
            $video->setVideoSecond(0);
            $video->setVideoHash($this->uniqHash);
            $video->setStatus(0);
            $video->setRemainingChunk($this->chunkTotal);

            $em->persist($video);
        }

        if($this->chunkIndex == 0) {
            $this->chunkIndex = $this->chunkIndex+1;
        }

        $videoChunk = new VideoChunk();
        $videoChunk->file = $this->uploadedFile;
        $videoChunk->setVideo($video);
        $videoChunk->setChunkIndex($this->chunkIndex);
        $videoChunk->setStatus(0);
        
        $em->persist($videoChunk);
        
        $remainingChunk = $this->chunkTotal-$this->chunkIndex;
        $video->setRemainingChunk($remainingChunk);

        $em->flush();

        

        $videoCount = count($video->getVideoChunks());

        if($videoCount == $this->chunkTotal) {
            // $video->setLastChunk($this->uploadedFile->)
            $bus->dispatch(new VideoMessage($video->getId()));
        }

        return $video;
    }

}