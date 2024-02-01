<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * @psalm-api
 */
class Attribute implements WithAnnotationInterface
{
    use WithAnnotation;

    public function __construct(
        #[SerializedName('@name')]
        public readonly string $name,
        #[SerializedName('@type')]
        public readonly BaseTypeEnum|string|null $type = null,
        #[SerializedName('@use')]
        public readonly UseEnum $use = UseEnum::Optional,
        #[SerializedName('@default')]
        public readonly ?string $default = null,
        public readonly ?SimpleType $simpleType = null,
    ) {}
}
