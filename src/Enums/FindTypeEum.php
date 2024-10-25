<?php

namespace Vertuoza\Enums;

enum FindTypeEum: string
{
    case FIND = 'find';
    case FIND_OR_FAILED = 'findOrFail';
}