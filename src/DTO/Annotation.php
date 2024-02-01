<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

readonly class Annotation
{
    /**
     * @param array<AppInfo>|null $appinfo
     * @param array<Documentation>|null $documentation
     */
    public function __construct(
        public ?array $appinfo = null,
        public ?array $documentation = null,
    ) {}
}
