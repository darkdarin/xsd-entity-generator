<?php

namespace DarkDarin\XsdEntityGenerator;

readonly class Context
{
    public function __construct(
        private string $namespace,
        private string $path
    ) {}

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function appendName(string $name): self
    {
        return new self($this->namespace . '\\' . $name, $this->path . DIRECTORY_SEPARATOR . $name);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getClassName(string $className): string
    {
        return $this->namespace . '\\' . $className;
    }

    public function getClassNamePath(string $className): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $className . '.php';
    }
}
