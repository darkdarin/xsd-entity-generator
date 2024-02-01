<?php

namespace DarkDarin\XsdEntityGenerator\Serializer;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class SchemaNameConverter implements NameConverterInterface
{
    public function normalize(string $propertyName): string
    {
        return 'xs:' . $propertyName;
    }

    public function denormalize(string $propertyName): string
    {
        return str_starts_with($propertyName, 'xs:') ? substr($propertyName, 3) : $propertyName;
    }
}
