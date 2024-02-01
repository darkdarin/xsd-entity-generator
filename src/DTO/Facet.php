<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

readonly class Facet
{
    public function __construct(
        #[SerializedName('@value')]
        public string $value,
    ) {}
}
