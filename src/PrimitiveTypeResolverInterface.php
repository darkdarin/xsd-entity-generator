<?php

namespace DarkDarin\XsdEntityGenerator;

use DarkDarin\PhpEntityRenderer\Contracts\TypeRendererInterface;
use DarkDarin\XsdEntityGenerator\DTO\PrimitiveTypeEnum;

interface PrimitiveTypeResolverInterface
{
    public function resolveType(PrimitiveTypeEnum $type): TypeRendererInterface;
}
