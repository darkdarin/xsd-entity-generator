<?php

namespace DarkDarin\XsdEntityGenerator;

use DarkDarin\PhpEntityRenderer\Contracts\TypeRendererInterface;
use DarkDarin\PhpEntityRenderer\Renderers\Types\BuiltinTypeRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\Types\ClassTypeRenderer;
use DarkDarin\XsdEntityGenerator\DTO\PrimitiveTypeEnum;

/**
 * @psalm-api
 */
readonly class PrimitiveTypeResolver implements PrimitiveTypeResolverInterface
{
    /**
     * @param class-string|null $dateTimeType
     * @param class-string|null $dateType
     * @param class-string|null $timeType
     * @param class-string|null $notationType
     * @param class-string|null $QNameType
     * @param class-string|null $anyURIType
     * @param class-string|null $hexBinaryType
     * @param class-string|null $base64BinaryType
     * @param class-string|null $GMonthType
     * @param class-string|null $GDayType
     * @param class-string|null $GMonthDayType
     * @param class-string|null $GYearMonthType
     * @param class-string|null $durationType
     */
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
            PrimitiveTypeEnum::Date => $this->dateType !== null ? new ClassTypeRenderer($this->dateType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::DateTime => $this->dateTimeType !== null ? new ClassTypeRenderer($this->dateTimeType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Time => $this->timeType !== null ? new ClassTypeRenderer($this->timeType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Notation => $this->notationType !== null ? new ClassTypeRenderer($this->notationType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::QName => $this->QNameType !== null ? new ClassTypeRenderer($this->QNameType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::AnyURI => $this->anyURIType !== null ? new ClassTypeRenderer($this->anyURIType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::HexBinary => $this->hexBinaryType !== null ? new ClassTypeRenderer($this->hexBinaryType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Base64Binary => $this->base64BinaryType !== null ? new ClassTypeRenderer($this->base64BinaryType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::GMonth => $this->GMonthType !== null ? new ClassTypeRenderer($this->GMonthType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::GDay => $this->GDayType !== null ? new ClassTypeRenderer($this->GDayType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::GMonthDay => $this->GMonthDayType !== null ? new ClassTypeRenderer($this->GMonthDayType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::GYearMonth => $this->GYearMonthType !== null ? new ClassTypeRenderer($this->GYearMonthType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Duration => $this->durationType !== null ? new ClassTypeRenderer($this->durationType) : BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::String => BuiltinTypeRenderer::String,
            PrimitiveTypeEnum::Boolean => BuiltinTypeRenderer::Bool,
            PrimitiveTypeEnum::Float, PrimitiveTypeEnum::Double => BuiltinTypeRenderer::Float,
            PrimitiveTypeEnum::Decimal => BuiltinTypeRenderer::Int,
        };
    }
}
