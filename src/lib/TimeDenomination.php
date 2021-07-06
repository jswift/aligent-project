<?php
class TimeDenomination {
  const SECOND = 'second';
  const MINUTE = 'minute';
  const HOUR = 'hour';
  const DAY = 'day';
  const WEEK = 'week';
  const YEAR = 'year';

  const options = [
    self::SECOND,
    self::MINUTE,
    self::HOUR,
    self::DAY,
    self::WEEK,
    self::YEAR,
  ];

  private const secondsInDenomination = [
    self::SECOND => 1,
    self::MINUTE => 60,
    self::HOUR => 3600,
    self::DAY => 86400,
    self::WEEK => 604800,
    self::YEAR => 31536000,
  ];

  public static function convertBetween($from, $fromType, $toType) {

    $toSeconds = self::convertToSeconds($from, $fromType);
    return self::convertFromSeconds($toSeconds, $toType);
  }

  private static function convertToSeconds($time, $fromType) {
    return self::secondsInDenomination[$fromType] * $time;
  }

  private static function convertFromSeconds($time, $toType) {
    return $time / self::secondsInDenomination[$toType];
  }
}
