<?php

namespace DarkDarin\XsdEntityGenerator\Serializer;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

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

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return is_string($data) && $format === XmlEncoder::FORMAT && class_exists($type) && !enum_exists($type);
    }

    /**
     * @param string|null $format
     * @return false[]
     * @psalm-suppress PossiblyUnusedMethod
     * @psalm-suppress PossiblyUnusedParam
     */
    public function getSupportedTypes(?string $format): array
    {
        return ['*' => false];
    }
}
