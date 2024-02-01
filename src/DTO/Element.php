<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class Element implements WithAnnotationInterface
{
    use WithAnnotation;

    public function __construct(
        #[SerializedName('@name')]
        public readonly string $name,
        #[SerializedName('@type')]
        public readonly BaseTypeEnum|string|null $type = null,
        public readonly ?ComplexType $complexType = null,
        public readonly ?SimpleType $simpleType = null,
        #[SerializedName('@minOccurs')]
        public readonly int $minOccurs = 1,
        #[SerializedName('@maxOccurs')]
        public readonly int|string $maxOccurs = 1,
    ) {}
}
