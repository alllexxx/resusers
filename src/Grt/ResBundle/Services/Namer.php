<?php

namespace Grt\ResBundle\Services;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
* Namer class
*/
class Namer implements NamerInterface
{
/**
* Creates a name for the file being uploaded.
*
* @param object          $object  The object the upload is attached to
* @param PropertyMapping $mapping The mapping to use to manipulate the given object
* @return string The file name
*/
    function name($obj, PropertyMapping $mapping)
    {
        $file = $obj->docFile;
        $extension = $file->guessExtension();

        return uniqid('', true).'.'.$extension;
    }
}