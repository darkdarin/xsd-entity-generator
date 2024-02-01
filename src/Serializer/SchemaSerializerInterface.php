<?php

namespace DarkDarin\XsdEntityGenerator\Serializer;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

interface SchemaSerializerInterface extends SerializerInterface, NormalizerInterface, DenormalizerInterface,
    EncoderInterface, DecoderInterface {}
