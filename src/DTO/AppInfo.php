<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

readonly class AppInfo
{
    public function __construct(
        #[SerializedName('#')]
        public ?string $content = null,
        #[SerializedName('@source')]
        public ?string $source = null,
    ) {}
}
