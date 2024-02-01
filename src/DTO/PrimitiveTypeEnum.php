<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

enum PrimitiveTypeEnum: string
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
    case Boolean = 'xs:boolean';
    case Base64Binary = 'xs:base64Binary';
    case HexBinary = 'xs:hexBinary';
    case Float = 'xs:float';
    case Decimal = 'xs:decimal';
    case Double = 'xs:double';
    case AnyURI = 'xs:anyURI';
    case QName = 'xs:QName';
    case Notation = 'xs:NOTATION';
}
