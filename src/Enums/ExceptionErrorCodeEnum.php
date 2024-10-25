<?php

namespace Vertuoza\Enums;

enum ExceptionErrorCodeEnum: int
{
    case BAD_REQUEST = 400;
    case INTERNAL_SERVER_ERROR = 500;
    case FORBIDDEN = 403;
    case UNAUTHORIZED = 401;
    case NOT_FOUND = 404;
}