<?php

namespace SiteBundle\Controller;

use SiteBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends Controller
{
    /**
     * @Route("upload_raw")
     */
    public function uploadRawAction(Request $request)
    {
        global $HTTP_POST_FILES;
        global $HTTP_RAW_POST_DATA;
        $entity = new File();
        $em = $this->getDoctrine()->getManager();
        fileDump(array($HTTP_POST_FILES, $_FILES, $HTTP_RAW_POST_DATA), true);
        try  {
            $entity->setFile($request->files->get('image'));
            $em->persist($entity);
            $em->flush();
        } catch (\Exception $e) {
            $em->close();
        }

            $webDir = $this->get('kernel')->getRootDir()."/../web";
            $fileWebPath = strtr($entity->getDirname(), array($webDir => ""));

        return new JsonResponse(array('error' => false, 'message' => 'uploaded!', 'path' => $fileWebPath."/".$entity->getName()));
    }

}
