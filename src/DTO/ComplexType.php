<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * @psalm-api
 */
class ComplexType implements NamedTypeInterface, WithAnnotationInterface
{
    use WithAnnotation;

    /**
     * @param array<Attribute>|null $attribute
     */
    public function __construct(
        #[SerializedName('@name')]
        public readonly ?string $name = null,
        public readonly ?Sequence $sequence = null,
        public readonly ?Sequence $all = null,
        public readonly ?Sequence $choice = null,
        public readonly ?array $attribute = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }
}
