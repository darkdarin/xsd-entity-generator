<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

trait WithAnnotation
{
    private ?Annotation $annotation = null;

    public function getAnnotation(): ?Annotation
    {
        return $this->annotation;
    }

    public function setAnnotation(Annotation $annotation): void
    {
        $this->annotation = $annotation;
    }

}
