<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

readonly class Sequence
{
    /**
     * @param array<Element>|null $element
     */
    public function __construct(
        public ?array $element = null,
    ) {}
}
