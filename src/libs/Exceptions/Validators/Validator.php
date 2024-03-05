<?php

namespace Vertuoza\Libs\Exceptions\Validators;

use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\FieldError;

abstract class Validator
{
  protected $field;
  protected $value;
  protected $path;
  protected $errors = [];

  public function __construct($field, $value, $path = "")
  {
    $this->field = $field;
    $this->value = $value;
    $this->path = $path;
  }

  /**
   * @return array<FieldError>
   */
  function validate(): array
  {
    return $this->errors;
  }

  function throwFirstError() {
	if (count($this->errors) > 0) {
	  throw new BadUserInputException($this->errors[0]);
	}
  }
}
