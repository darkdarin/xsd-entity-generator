<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

/**
 * @psalm-api
 */
readonly class Annotation
{
    /**
     * @param list<AppInfo> $appinfo
     * @param list<Documentation> $documentation
     */
    public function __construct(
        public array $appinfo = [],
        public array $documentation = [],
    ) {}
}
