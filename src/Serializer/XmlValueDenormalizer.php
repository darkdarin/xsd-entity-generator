<?php

namespace DarkDarin\XsdEntityGenerator\Serializer;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @method array getSupportedTypes(?string $format)
 */
class XmlValueDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (is_string($data) && $format === XmlEncoder::FORMAT && class_exists($type) && !enum_exists($type)) {
            return $this->denormalizer->denormalize(['#' => $data], $type, $format, $context);
        }

        return $data;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return is_string($data) && $format === XmlEncoder::FORMAT && class_exists($type) && !enum_exists($type);
    }
}
