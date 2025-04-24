<?php

namespace Vertuoza\Libs\Exceptions\Validators;

use Vertuoza\Libs\Exceptions\FieldError;

class StringValidator extends Validator
{
  public function __construct($field, $value, $path = "")
  {
    parent::__construct($field, $value, $path);
  }

  /**
   * Check that value is not empty
   *
   * @param boolean $trimmed
   *
   * @return self
   */
  public function notEmpty(bool $trimmed = false)
  {
    $value = $trimmed ? trim($this->value) : $this->value;
    if (empty($value)) {
      $this->errors[] = new FieldError($this->field, "Field cannot be empty", "EMPTY", $this->path);
    }

    return $this;
  }

  /**
   * Check that value has a maximum number of characters
   *
   * @param integer $max
   *
   * @return self
   */
  public function max(int $max)
  {
    if (isset($this->value) && strlen($this->value) > $max) {
      $this->errors[] = new FieldError($this->field, "Field cannot be longer than $max characters", "MAX_LENGTH", $this->path, ["max" => $max]);
    }

    return $this;
  }

  /**
   * Check that value has a minimum number of characters
   *
   * @param integer $min
   *
   * @return self
   */
  public function min(int $min)
  {
    if (isset($this->value) && strlen($this->value) > $min) {
      $this->errors[] = new FieldError($this->field, "Field cannot be less than $min characters", "MIN_LENGTH", $this->path, ["min" => $min]);
    }

    return $this;
  }

  /**
   * Check that value has the right format
   *
   * @param string $format
   *
   * @return self
   */
  public function format(string $format, string $allowedFormat = ''): self
  {
    if (isset($this->value) && empty(preg_match($format, $this->value))) {
      $this->errors[] = new FieldError($this->field, "Field mismatch with the allowed format : $allowedFormat", "FORMAT", $this->path, ["allowed" => $allowedFormat]);
    }

    return $this;
  }
}
