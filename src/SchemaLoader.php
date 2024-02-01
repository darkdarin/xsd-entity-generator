<?php

namespace DarkDarin\XsdEntityGenerator;

use DarkDarin\XsdEntityGenerator\DTO\Schema;
use DarkDarin\XsdEntityGenerator\Serializer\SchemaSerializerInterface;

readonly class SchemaLoader
{
    public function __construct(
        private SchemaSerializerInterface $serializer,
    ) {}

    public function load(string $path): Schema
    {
        $xsd = file_get_contents($path);
        return $this->serializer->deserialize($xsd, Schema::class, 'xml');
    }
}
