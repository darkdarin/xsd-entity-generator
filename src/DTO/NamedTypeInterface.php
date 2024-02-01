<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

/**
 * @psalm-api
 */
interface NamedTypeInterface
{
    /**
     * @psalm-mutation-free
     */
    public function getName(): ?string;
}
