<?php
namespace App\Util;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        return $object->getId();
    }
}