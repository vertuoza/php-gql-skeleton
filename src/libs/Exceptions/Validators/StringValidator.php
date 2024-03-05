<?php

namespace Vertuoza\Libs\Exceptions\Validators;

use Vertuoza\Libs\Exceptions\FieldError;

class StringValidator extends Validator
{
  public function __construct($field, $value, $path = "")
  {
    parent::__construct($field, $value, $path);
  }

  public function notEmpty(bool $trimmed = false)
  {
    $value = $trimmed ? trim($this->value) : $this->value;
    if (empty($value)) {
      $this->errors[] = new FieldError($this->field, "Field cannot be empty", "EMPTY", $this->path);
    }

    return $this;
  }

  public function isUuid(bool $trimmed = false)
  {
    $value = $trimmed ? trim($this->value) : $this->value;
    if (preg_match('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/gm',$value)) {
      $this->errors[] = new FieldError($this->field, "Field is not a valid UUID", "EMPTY", $this->path);
	}

    return $this;
  }

  public function maxLength(int $length, bool $trimmed = false)
  {
    $value = $trimmed ? trim($this->value) : $this->value;
	if (strlen($value) > $length) {
	  $this->errors[] = new FieldError($this->field, "Field cannot be longer than $length characters", "EMPTY", $this->path);
	}

	return $this;
  }

  public function max(int $max)
  {
    if (isset($this->value) && strlen($this->value) > $max) {
      $this->errors[] = new FieldError($this->field, "Field cannot be longer than $max characters", "MAX_LENGTH", $this->path, ["max" => $max]);
    }

    return $this;
  }

  public function min(int $min)
  {
    if (isset($value) && strlen($this->value) > $min) {
      $this->errors[] = new FieldError($this->field, "Field cannot be less than $min characters", "MIN_LENGTH", $this->path, ["min" => $min]);
    }

    return $this;
  }
}
