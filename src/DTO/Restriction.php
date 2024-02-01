<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * @psalm-api
 */
readonly class Restriction
{
    /**
     * @param array<Facet> $enumeration
     * @param array<Facet> $pattern
     */
    public function __construct(
        #[SerializedName('@base')]
        public BaseTypeEnum|string $base,
        public ?Facet $minExclusive = null,
        public ?Facet $minInclusive = null,
        public ?Facet $maxExclusive = null,
        public ?Facet $maxInclusive = null,
        public ?Facet $totalDigits = null,
        public ?Facet $fractionDigits = null,
        public ?Facet $length = null,
        public ?Facet $minLength = null,
        public ?Facet $maxLength = null,
        public ?array $enumeration = null,
        public ?Facet $whiteSpace = null,
        public ?array $pattern = null,
    ) {}
}
