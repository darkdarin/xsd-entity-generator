<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

enum BaseTypeEnum: string
{
    case AnyType = 'xs:anyType';
    case Duration = 'xs:duration';
    case DateTime = 'xs:dateTime';
    case Time = 'xs:time';
    case Date = 'xs:date';
    case GYearMonth = 'xs:gYearMonth';
    case GMonthDay = 'xs:gMonthDay';
    case GDay = 'xs:gDay';
    case GMonth = 'xs:gMonth';
    case String = 'xs:string';
    case NormalizedString = 'xs:normalizedString';
    case Token = 'xs:token';
    case Language = 'xs:language';
    case Name = 'xs:Name';
    case NCName = 'xs:NCName';
    case Id = 'xs:ID';
    case IdRef = 'xs:IDREF';
    case IdRefs = 'xs:IDREFS';
    case Entity = 'xs:ENTITY';
    case Entities = 'xs:ENTITIES';
    case NMToken = 'xs:NMTOKEN';
    case NMTokens = 'xs:NMTOKENS';
    case Boolean = 'xs:boolean';
    case Base64Binary = 'xs:base64Binary';
    case HexBinary = 'xs:hexBinary';
    case Float = 'xs:float';
    case Decimal = 'xs:decimal';
    case Integer = 'xs:integer';
    case NonPositiveInteger = 'xs:nonPositiveInteger';
    case NegativeInteger = 'xs:negativeInteger';
    case Long = 'xs:long';
    case Int = 'xs:int';
    case Short = 'xs:short';
    case Byte = 'xs:byte';
    case NonNegativeInteger = 'xs:nonNegativeInteger';
    case PositiveInteger = 'xs:positiveInteger';
    case UnsignedLong = 'xs:unsignedLong';
    case UnsignedInt = 'xs:unsignedInt';
    case UnsignedShort = 'xs:unsignedShort';
    case UnsignedByte = 'xs:unsignedByte';
    case Double = 'xs:double';
    case AnyURI = 'xs:anyURI';
    case QName = 'xs:QName';
    case Notation = 'xs:NOTATION';

    public function getPrimitiveType(): PrimitiveTypeEnum
    {
        return match ($this) {
            self::AnyType => PrimitiveTypeEnum::AnyType,
            self::Duration => PrimitiveTypeEnum::Duration,
            self::DateTime => PrimitiveTypeEnum::DateTime,
            self::Time => PrimitiveTypeEnum::Time,
            self::Date => PrimitiveTypeEnum::Date,
            self::GYearMonth => PrimitiveTypeEnum::GYearMonth,
            self::GMonthDay => PrimitiveTypeEnum::GMonthDay,
            self::GDay => PrimitiveTypeEnum::GDay,
            self::GMonth => PrimitiveTypeEnum::GMonth,
            self::String, self::NormalizedString, self::Token,
            self::Language, self::Name, self::NCName, self::Id,
            self::IdRef, self::IdRefs, self::Entity, self::Entities,
            self::NMToken, self::NMTokens => PrimitiveTypeEnum::String,
            self::Boolean => PrimitiveTypeEnum::Boolean,
            self::Base64Binary => PrimitiveTypeEnum::Base64Binary,
            self::HexBinary => PrimitiveTypeEnum::HexBinary,
            self::Float => PrimitiveTypeEnum::Float,
            self::Decimal, self::UnsignedByte, self::UnsignedShort, self::UnsignedInt,
            self::UnsignedLong, self::PositiveInteger, self::NonNegativeInteger,
            self::Byte, self::Short, self::Int, self::Long,
            self::NegativeInteger, self::NonPositiveInteger, self::Integer => PrimitiveTypeEnum::Decimal,
            self::Double => PrimitiveTypeEnum::Double,
            self::AnyURI => PrimitiveTypeEnum::AnyURI,
            self::QName => PrimitiveTypeEnum::QName,
            self::Notation => PrimitiveTypeEnum::Notation,
        };
    }
}
