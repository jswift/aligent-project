<?php

/*
* Validate an ISO8601 date
*/
class Iso8601DateValidator {
  public static function validate($val) {
    $v = DateTime::createFromFormat(DATE_ISO8601, $val);
    return $v ? true : false;
  }
}
