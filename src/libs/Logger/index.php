<?php

namespace Vertuoza\Libs\Logger;

use Throwable;
use Vertuoza\Libs\Logger\ApplicationLogger;

require_once __DIR__ . "/ApplicationLogger.php";
require_once __DIR__ . "/GcpLoggerFormatter.php";
require_once __DIR__ . "/LogContext.php";


function startLogger()
{
  $logger = ApplicationLogger::getInstance();
  set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $logger = ApplicationLogger::getInstance();
    if (!(error_reporting() & $errno)) {
      // This error code is not included in error_reporting, so let it fall
      // through to the standard PHP error handler
      $logger->warning($errstr, null, ['errline' => $errline, 'errfile' => $errfile]);
      return false;
    }

    // $errstr may need to be escaped:
    $errstr = htmlspecialchars($errstr);


    switch ($errno) {
      case E_CORE_ERROR:
      case E_COMPILE_ERROR:
      case E_ERROR:
      case E_USER_ERROR:
        $message = "[$errno] $errstr\r\nFatal error on line $errline in file $errfile";
        $logger->error($message, 'INTERNAL', null, ['errline' => $errline, 'errfile' => $errfile]);
        break;

      case E_CORE_WARNING:
      case E_WARNING:
      case E_COMPILE_WARNING:
      case E_USER_WARNING:
        $message = "[$errno] $errstr\r\nWarning on line $errline in file $errfile";
        $logger->warning($message, null, ['errline' => $errline, 'errfile' => $errfile]);
        break;
      case E_DEPRECATED:
        $message = "[$errno] $errstr\r\nDeprecated on line $errline in file $errfile";
        $logger->warning($message, null, ['errline' => $errline, 'errfile' => $errfile]);
        break;

      case E_USER_NOTICE:
        $message = "[$errno] $errstr\r\nNotice error on line $errline in file $errfile";
        $logger->info($message, null, ['errline' => $errline, 'errfile' => $errfile]);
        break;

      default:
        $message = "[$errno] $errstr\r\nError on line $errline in file $errfile";
        $logger->warning($message, null, ['errline' => $errline, 'errfile' => $errfile]);
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
  });

  set_exception_handler(function (Throwable $exception) {
    $logger = ApplicationLogger::getInstance();
    $logger->error($exception, $exception->getCode(), null, ['exception' => $exception]);
  });
  return $logger;
}
