<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

/**
 * @psalm-api
 */
interface WithAnnotationInterface
{
    public function getAnnotation(): ?Annotation;
}
