<?php

namespace SiteBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\File\File as HttpFoundationFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use SiteBundle\Entity\File;
use Iphp\FileStoreBundle\Driver\AnnotationDriver;
use Iphp\FileStoreBundle\Mapping\Annotation\UploadableField;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Vich\UploaderBundle\Metadata\MetadataReader;
use JMS\Serializer\Serializer;


class UploadListener
{

    /** @var MetadataReader */
    public $metadataReader;
    public $pAccess;

    public function __construct(MetadataReader $metadataReader, Serializer $serializer) {
        $this->metadataReader = $metadataReader;
        $this->pAccess = PropertyAccess::createPropertyAccessor();
        $this->serializer = $serializer;
    }

    public function prePersist(LifecycleEventArgs $eventArgs){
        $this->convertFileToEntity($eventArgs);
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs){
        $this->convertFileToEntity($eventArgs);
    }

    public function getValue($object, $name) {
        return $this->pAccess->getValue($object, $name);
    }

    public function setValue($object, $name, $value) {
        $this->pAccess->setValue($object, $name, $value);
    }

//    public function propertyIsWritable($object, $name) {
//        return $this->pAccess->isWritable($object, $name);
//    }

    public function getClass($object) {
        return ClassUtils::getRealClass(get_class($object));
    }

//    public function isWritableProperty() {
//
//    }

    public function propertyIsWritable($object, $property_name, $check_lower = true) {
        if ($check_lower) {
            if ($this->pAccess->isWritable($object, $property_name))
                return $property_name;
            else if ($this->pAccess->isWritable($object, $lower_property_name = strtolower($property_name)))
                return $lower_property_name;
        } else {
            if ($this->pAccess->isWritable($object, $property_name)) {
                return $property_name;
            }
        }
        return false;
    }

    public function getHashedName(HttpFoundationFile $file) {
        return md5(uniqid().$file->getFilename());
    }

    public function isFile($file) {
        return is_a($file, 'Symfony\Component\HttpFoundation\File\File');
    }

    public function fileToArray(HttpFoundationFile $file) {
        return array(
            'name' => $file->getFilename(),/*$this->getHashedName($file).".".$file->getExtension(),*/
            'dirname' => $file->getPath(),
            /*'originalName' => $file->getFilename()*/
            'extension' => $file->getExtension(),
            'mimetype' => $file->getMimeType()
        );
    }

    public function convertFileToEntity($eventArgs) {

        /** @var $entity File*/

        if (method_exists($eventArgs, 'getEntity')) {
            $entity = $eventArgs->getEntity();
            $class = $this->getClass($entity);
            if ($this->metadataReader->isUploadable($class)) {
                $uploadableFields = $this->metadataReader->getUploadableFields($class);
                if (!empty($uploadableFields)) {
                    foreach ($uploadableFields as $name => $aField) {
                        /** @var $uploadedFile HttpFoundationFile */
                        $uploadedFile = $this->pAccess->getValue($entity, $name);
                        if ($this->isFile($uploadedFile)) {
                            $uploadedArray = $this->fileToArray($uploadedFile);
                            foreach ($uploadedArray as $property => $value) {
                                //fDump(array($property, $entity, $property == 'fileName', $this->hasProperty($entity, 'name'), property_exists($entity, 'name')));
                                /*if ($property == 'fileName' && $this->propertyIsWritable($entity, 'name')) {
                                    $this->setValue($entity, 'name', ltrim($value,"/"));
                                } else if ($property == 'path' && $this->propertyIsWritable($entity, 'dirname')) {
                                    $this->setValue($entity, 'dirname', dirname($value));
                                } else {*/
                                $property_name = $this->propertyIsWritable($entity, $property);
                                if ($property_name) {
                                    $this->setValue($entity, $property_name, $value);
                                }
                                /*}*/
                            }
                        }
                    }
                }
            }

            /*$uploadable = $this->driver->readUploadable($entity_class);

            if (!is_null($uploadable)) {
                $uploadableFields = $this->driver->readUploadableFields($entity_class);
                foreach ($uploadableFields as $field) {
                    if (get_class($field) == 'Iphp\FileStoreBundle\Mapping\Annotation\UploadableField') {
                        $field_name = $field->getFileUploadPropertyName();
                        $uploadedFile = $this->getValue($entity, $field_name);

                        foreach ($uploadedFile as $property => $value) {
                            //fDump(array($property, $entity, $property == 'fileName', $this->hasProperty($entity, 'name'), property_exists($entity, 'name')));
                            if ($property == 'fileName' && $this->propertyIsWritable($entity, 'name')) {
                                $this->setValue($entity, 'name', ltrim($value,"/"));
                            } else if ($property == 'path' && $this->propertyIsWritable($entity, 'dirname')) {
                                $this->setValue($entity, 'dirname', dirname($value));
                            } else {
                                $property_name = $this->propertyIsWritable($entity, $property);
                                if ($property_name) {
                                    $this->setValue($entity, $property_name, $value);
                                }
                            }
                        }
                    }
                }
            }*/
        }
    }
}