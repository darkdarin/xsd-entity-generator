<?php

namespace DarkDarin\XsdEntityGenerator;

use DarkDarin\PhpEntityRenderer\Contracts\TypeRendererInterface;
use DarkDarin\PhpEntityRenderer\Renderers\Types\BuiltinTypeRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\Types\ClassTypeRenderer;
use DarkDarin\XsdEntityGenerator\DTO\PrimitiveTypeEnum;

readonly class PrimitiveTypeResolver implements PrimitiveTypeResolverInterface
{
    public function __construct(
        private ?string $dateTimeType = null,
        private ?string $dateType = null,
        private ?string $timeType = null,
        private ?string $notationType = null,
        private ?string $QNameType = null,
        private ?string $anyURIType = null,
        private ?string $hexBinaryType = null,
        private ?string $base64BinaryType = null,
        private ?string $GMonthType = null,
        private ?string $GDayType = null,
        private ?string $GMonthDayType = null,
        private ?string $GYearMonthType = null,
        private ?string $durationType = null,
    ) {}

    public function resolveType(PrimitiveTypeEnum $type): TypeRendererInterface
    {
        return match ($type) {
            PrimitiveTypeEnum::AnyType => BuiltinTypeRenderer::Mixed,
            PrimitiveTypeEnum::Date => $this->dateType ? new ClassTypeRenderer($this->dateType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::DateTime => $this->dateType ? new ClassTypeRenderer($this->dateTimeType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Time => $this->dateType ? new ClassTypeRenderer($this->timeType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Notation => $this->notationType ? new ClassTypeRenderer($this->notationType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::QName => $this->QNameType ? new ClassTypeRenderer($this->QNameType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::AnyURI => $this->anyURIType ? new ClassTypeRenderer($this->anyURIType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::HexBinary => $this->hexBinaryType ? new ClassTypeRenderer($this->hexBinaryType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Base64Binary => $this->base64BinaryType ? new ClassTypeRenderer($this->base64BinaryType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::GMonth => $this->GMonthType ? new ClassTypeRenderer($this->GMonthType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::GDay => $this->GDayType ? new ClassTypeRenderer($this->GDayType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::GMonthDay => $this->GMonthDayType ? new ClassTypeRenderer($this->GMonthDayType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::GYearMonth => $this->GYearMonthType ? new ClassTypeRenderer($this->GYearMonthType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Duration => $this->durationType ? new ClassTypeRenderer($this->durationType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::String => BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Boolean => BuiltinTypeRenderer::Bool,
            PrimitiveTypeEnum::Float, PrimitiveTypeEnum::Double => BuiltinTypeRenderer::Float,
            PrimitiveTypeEnum::Decimal => BuiltinTypeRenderer::Int,
        };
    }
}
