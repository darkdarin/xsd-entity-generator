<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * @psalm-api
 */
readonly class Facet
{
    public function __construct(
        #[SerializedName('@value')]
        public string $value,
    ) {}
}
