<?php

namespace Vertuoza\Libs\Exceptions;

use Throwable;

// To use when the user input contains data that not allowed the process to continue. Due to business rules
class BadUserInputException extends BadRequestException
{
  public const code = 400;
  /** 
   * @var array<FieldError>
   */
  private array $fieldsError;


  /**
   * @param Array|FieldError $fieldsError The fields that contains the error
   * @param Throwable $previous
   * @param array|null $args
   */
  public function __construct(array|FieldError $fieldsError, Throwable $previous = null, array|null $args = null)
  {
    $this->fieldsError = is_array($fieldsError) ? $fieldsError : [$fieldsError];
    $message = "Bad user input for field `{$fieldsError->field}`";
    parent::__construct($message, "BAD_USER_INPUT", $previous, $args);
  }

  /**
   * @return array<FieldError>
   */
  public function getFieldsError(): array
  {
    return $this->fieldsError;
  }
}
