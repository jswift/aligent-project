<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class TimeDenominationTest extends TestCase
{
  /*
  * Ensure that the amount of seconds in each time denomination is correct
  */
  public function testSecondsInDenomination()
  {
    $secondsInDenomination = [
      TimeDenomination::SECOND => 1,
      TimeDenomination::MINUTE => 60,
      TimeDenomination::HOUR   => 60 * 60,
      TimeDenomination::DAY    => 60 * 60 * 24,
      TimeDenomination::WEEK   => 60 * 60 * 24 * 7,
      TimeDenomination::YEAR   => 60 * 60 * 24 * 365,
    ];

    foreach ($secondsInDenomination as $denomination => $seconds) {
      $this->assertEquals(TimeDenomination::convertBetween($seconds, TimeDenomination::SECOND, TimeDenomination::SECOND), $seconds);
    }
  }

  /*
  * Ensure that time conversion is working as expected
  */
  public function testConversion() {
    $convert = [
      [
        'fromValue' => 7,
        'fromType' => TimeDenomination::DAY,
        'toValue'  => 1,
        'toType'   => TimeDenomination::WEEK,
      ],
      [
        'fromValue' => 364,
        'fromType' => TimeDenomination::DAY,
        'toValue'  => 52,
        'toType'   => TimeDenomination::WEEK,
      ],
      [
        'fromValue' => 1,
        'fromType' => TimeDenomination::WEEK,
        'toValue'  => 604800,
        'toType'   => TimeDenomination::SECOND,
      ],
      [
        'fromValue' => 2,
        'fromType' => TimeDenomination::YEAR,
        'toValue'  => 60 * 60 * 24 * 365 * 2,
        'toType'   => TimeDenomination::SECOND,
      ],
      [
        'fromValue' => 2,
        'fromType' => TimeDenomination::MINUTE,
        'toValue'  => 120,
        'toType'   => TimeDenomination::SECOND,
      ],
    ];

    foreach($convert as $test) {
      $this->assertEquals(TimeDenomination::convertBetween($test['fromValue'], $test['fromType'], $test['toType']), $test['toValue']);
    }
  }
}

