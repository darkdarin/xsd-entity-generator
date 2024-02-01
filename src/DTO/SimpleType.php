<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class SimpleType implements NamedTypeInterface, WithAnnotationInterface
{
    use WithAnnotation;

    public function __construct(
        #[SerializedName('@name')]
        public readonly ?string $name = null,
        public readonly ?Restriction $restriction = null
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }
}
