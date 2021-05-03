<?php

namespace App\EventListener;

use Gedmo\Timestampable\TimestampableListener AS GedmoTimestampableListener;

/**
 * Extension of Gedmo TimestamplabeListner
 */
class TimestampableListener extends GedmoTimestampableListener
{

    protected function updateField($object, $eventAdapter, $meta, $field)
    {
        /** @var \Doctrine\Orm\Mapping\ClassMetadata $meta */
        $property = $meta->getReflectionProperty($field);
        $newValue = $this->getFieldValue($meta, $field, $eventAdapter);

        if (!$object->isTimestampableCanceled()) {
            $property->setValue($object, $newValue);
        }
    }
}