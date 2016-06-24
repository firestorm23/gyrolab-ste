<?php
// src/AppBundle/Form/DataTransformer/IssueToNumberTransformer.php
namespace SiteBundle\Form\DataTransformer;

use SiteBundle\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Serializer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ArrayToFileTransformer implements DataTransformerInterface
{
    private $manager;
    private $entity_class;
    private $web_dir;
    private $serializer;

    public function __construct(/*ObjectManager $manager, $entity_class,*/ /*$web_dir *//*Serializer $serializer*/)
    {
//        $this->manager = $manager;
//        $this->entity_class = $entity_class;
        $this->web_dir = "/var/www/virtual/cityman.top";
//        $this->serializer = $serializer;
    }

    /**
     * Transforms an object (File) to a file (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($file)
    {
//        fileDump($file, true);
//        if (is_file($this->web_dir.$file['path'])) {
//            return new File($this->web_dir.$file['path']);
//        }
 //       fileDump(array($file, 'zozoz1'), true);
        return null;
    }

    public function getRelativePath ($absolute_path) {
        return str_replace($this->web_dir, "", $absolute_path);
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $issueNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($file)
    {
        //fileDump(array($file, 'zozoz2'), true);
        return $file;
        //return new UploadedFile($this->web_dir.$file['path'], $file['originalName'], $file['mimeType'], $file['size']);
    }
}
?>