<?php

namespace DarkDarin\XsdEntityGenerator\Serializer;

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @psalm-api
 */
class SchemaSerializerFactory
{
    public function __invoke(): SchemaSerializerInterface
    {
        return new SchemaSerializer($this->getDefaultNormalizers(), [new XmlEncoder()]);
    }

    /**
     * @return list<DenormalizerInterface|NormalizerInterface>
     */
    private function getDefaultNormalizers(): array
    {
        $classMetadataFactory = new ClassMetadataFactory(
            new AttributeLoader(),
        );

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory, new SchemaNameConverter());

        $extractor = new PropertyInfoExtractor([], [
            new PhpDocExtractor(),
            new ReflectionExtractor(),
        ]);

        $context = [
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            AbstractObjectNormalizer::PRESERVE_EMPTY_OBJECTS => true,
        ];

        $discriminator = new ClassDiscriminatorFromClassMetadata($classMetadataFactory);

        return [
            new ArrayDenormalizer(),
            new XmlValueDenormalizer(),
            new BackedEnumNormalizer(),
            new JsonSerializableNormalizer(),
            new ObjectNormalizer(
                classMetadataFactory:       $classMetadataFactory,
                nameConverter:              $metadataAwareNameConverter,
                propertyTypeExtractor:      $extractor,
                classDiscriminatorResolver: $discriminator,
                defaultContext:             $context,
            ),
        ];
    }
}
