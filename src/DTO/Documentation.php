<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * @psalm-api
 */
readonly class Documentation
{
    public function __construct(
        #[SerializedName('#')]
        public ?string $content = null,
        #[SerializedName('@source')]
        public ?string $source = null,
        #[SerializedName('@xml:lang')]
        public ?string $lang = null,
    ) {}
}
