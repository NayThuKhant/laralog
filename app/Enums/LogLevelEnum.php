<?php

namespace App\Enums;

enum LogLevelEnum: string
{
    case EMERGENCY = "EMERGENCY";
    case ALERT = "ALERT";
    case CRITICAL = "CRITICAL";
    case ERROR = "ERROR";
    case WARNING = "WARNING";
    case NOTICE = "NOTICE";
    case INFORMATIONAL = "INFORMATIONAL";
    case DEBUG = "DEBUG";
}
