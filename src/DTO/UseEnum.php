<?php

namespace DarkDarin\XsdEntityGenerator\DTO;

enum UseEnum: string
{
    case Prohibited = 'prohibited';
    case Optional = 'optional';
    case Required = 'required';
}
