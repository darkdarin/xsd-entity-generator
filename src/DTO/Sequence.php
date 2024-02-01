<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

/**
 * @psalm-api
 */
readonly class Sequence
{
    /**
     * @param array<Element>|null $element
     */
    public function __construct(
        public ?array $element = null,
    ) {}
}
