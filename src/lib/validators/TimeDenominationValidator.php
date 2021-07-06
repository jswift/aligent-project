<?php

/*
* Validate a time denomination to ensure its supported by the project
*/
class TimeDenominationValidator {
  public static function validate($val) {
    return in_array($val, TimeDenomination::options);
  }
}
