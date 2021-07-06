<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class Iso8601DateValidatorTest extends TestCase
{
  /*
  * Ensure ISO8601DateValidator works as expected
  */
  public function testISO8601Validator()
  {
    $conversion = [
      [
        'date' => '2021-07-06T09:34:30+09:00',
        'result' => true,
      ],
      [
        'date' => '2021-07-06T09:34:30+00:00',
        'result' => true,
      ],
      [
        'date' => '2021-07-72T09:34:30+00:00',
        'result' => false,
      ],
      [
        'date' => 'foo',
        'result' => false,
      ],
      [
        'date' => 123456,
        'result' => false,
      ],
      [
        'date' => "@123456",
        'result' => false,
      ],
    ];

    foreach($conversion as $test) {
      $this->assertEquals(Iso8601DateValidator::validate($test['date']), $test['result']);
    }

  }
}
