<?php

namespace App\Utils;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerUtils
{
    public static function circularReferenceHandler(object $object)
    {
        return $object->getId();
    }

    public static function serializeWithCircularReference(mixed $object)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $object = $serializer->serialize($object, 'json', [
            'circular_reference_handler' => [self::class, 'circularReferenceHandler']
        ]);

        return json_decode($object);
    }
}