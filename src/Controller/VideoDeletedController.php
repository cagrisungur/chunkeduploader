<?php

namespace App\Controller;

use App\Entity\Video;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class VideoDeletedController extends AbstractController
{
    public function __invoke(Video $video, Request $request) 
    {
        if(!$request->get('id')) {
            throw new BadRequestHttpException('"id" is required');
        }
        $thumnnailDir = $this->getParameter('thumbnail_dir');
        $em = $this->getDoctrine()->getManager();

        $video = $em->getRepository(Video::class)->find($request->get('id'));

        unlink($thumnnailDir.$video->getVideoHash().".jpg");
        
        return new JsonResponse(array(
            "STATUS" => 200,
            "MESSAGE" => "SUCCESS"
        ));
    }
}