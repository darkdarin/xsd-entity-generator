<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

readonly class Schema
{
    /**
     * @param array<Element> $element
     * @param array<ComplexType> $complexType
     * @param array<SimpleType> $simpleType
     */
    public function __construct(
        public array $element = [],
        public array $complexType = [],
        public array $simpleType = [],
    ) {}
}
