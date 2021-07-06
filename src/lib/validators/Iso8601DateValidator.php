<?php

/*
* Validate an ISO8601 date
*/
class Iso8601DateValidator {
  public static function validate($val) {
    $v = DateTime::createFromFormat(DATE_ISO8601, $val);

    // format and comparison is because php can (for some reason) think that this date is valid. 2021-07-72T09:34:30+00:00 (note the 72nd day of the month)
    // the check confirms that PHP has gotten it all correct
    return $v && $v->format('c') === trim($val) ? true : false;
  }
}
