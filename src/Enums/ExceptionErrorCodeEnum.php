<?php

namespace Vertuoza\Enums;

enum ExceptionErrorCodeEnum: int
{
    case UNDEFINED = -1;

    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case INTERNAL_SERVER_ERROR = 500;

}