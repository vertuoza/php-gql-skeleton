<?php

namespace Vertuoza\Libs\Exceptions\Validators;

use Vertuoza\Libs\Exceptions\FieldError;

class StringValidator extends Validator
{
  public function __construct($field, $value, $path = "")
  {
    parent::__construct($field, $value, $path);
  }

  public function notEmpty(bool $trimmed = false): static
  {
    $value = $trimmed ? trim($this->value) : $this->value;
    if (empty($value)) {
      $this->errors[] = new FieldError($this->field, "Field cannot be empty", "EMPTY", $this->path);
    }

    return $this;
  }

  public function max(int $max): static
  {
    if (isset($this->value) && strlen($this->value) > $max) {
      $this->errors[] = new FieldError($this->field, "Field cannot be longer than $max characters", "MAX_LENGTH", $this->path, ["max" => $max]);
    }

    return $this;
  }

  public function min(int $min): static
  {
    if (isset($this->value) && strlen($this->value) < $min) {
      $this->errors[] = new FieldError($this->field, "Field cannot be less than $min characters", "MIN_LENGTH", $this->path, ["min" => $min]);
    }

    return $this;
  }

    /**
     * @param string $regex
     * @param string|null $message
     * @return $this
     */
  public function matches(string $regex, ?string $message = null): static
  {
    if (isset($this->value) && !preg_match($regex, $this->value)) {
      $this->errors[] = new FieldError($this->field, $message ?? "Field contains invalid characters", "INVALID_CHARACTERS", $this->path);
    }

    return $this;
  }
}
