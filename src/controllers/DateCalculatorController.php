<?php
class DateCalculatorController extends BaseController {

  /*
  * API endpoint to count the days between two dates
  */
  public function countDaysBetweenDates() {
    $service = $this->klein->service();

    // perform some date format validation
    $service->validateParam('from', 'Please enter a valid \'from\' field')
      ->notNull()
      ->isIso8601Date();

    $service->validateParam('to', 'Please enter a valid \'to\' field')
      ->notNull()
      ->isIso8601Date();

    // convert the string to a date object
    $from = DateTime::createFromFormat(DATE_ISO8601, $this->klein->request()->from);
    $to = DateTime::createFromFormat(DATE_ISO8601, $this->klein->request()->to);

    // calculate the diff between the two dates
    $diff = $from->diff($to);

    // construct and send the response
    $response = new stdClass();
    $response->daysDiff = $diff->days;
    return $this->klein->response()->json($response);
  }

  /*
  * API endpoint to determine if the paramater DateTime is a week day (mon-friday)
  */
  private function isWeekday(DateTime $dt): bool {
    $dow = intval($dt->format("w"), 10);
    return $dow !== 0 && $dow !== 6;
  }

  public function countWeekdaysBetweenDates() {
    $service = $this->klein->service();

    // perform some date format validation
    $service->validateParam('from', 'Please enter a valid \'from\' field')
      ->notNull()
      ->isIso8601Date();

    $service->validateParam('to', 'Please enter a valid \'to\' field')
      ->notNull()
      ->isIso8601Date();

    // convert the string to a date object
    $from = DateTime::createFromFormat(DATE_ISO8601, $this->klein->request()->from);
    $to = DateTime::createFromFormat(DATE_ISO8601, $this->klein->request()->to);

    // order the dates from min to max
    $order = [
      $from->format('U') => $from,
      $to->format('U') => $to
    ];
    ksort($order, SORT_NUMERIC);

    // iterate between the two dates figuring out which days are weekdays
    $max = array_pop($order);
    $min = array_pop($order);

    $weekdays = 0;
    for ($i = $min; $i < $max; $i->modify('+1 day')) {
      if ($this->isWeekday($i)) {
        $weekdays++;
      }
    }

    // construct and send the response
    $response = new stdClass();
    $response->weekdaysDiff = $weekdays;
    return $this->klein->response()->json($response);
  }

  /*
  * API endpoint to determine the amount of full weeks between two dates
  */
  public function countWeeksBetweenDates() {
    $service = $this->klein->service();

    // perform some date format validation
    $service->validateParam('from', 'Please enter a valid \'from\' field')
      ->notNull()
      ->isIso8601Date();

    $service->validateParam('to', 'Please enter a valid \'to\' field')
      ->notNull()
      ->isIso8601Date();

    // convert the string to a date object
    $from = DateTime::createFromFormat(DATE_ISO8601, $this->klein->request()->from);
    $to = DateTime::createFromFormat(DATE_ISO8601, $this->klein->request()->to);

    // order the dates from min to max
    $order = [
      $from->format('U') => $from,
      $to->format('U') => $to
    ];
    ksort($order, SORT_NUMERIC);

    $max = array_pop($order);
    $min = array_pop($order);

    // shift the dates to their respective mondays so we only have full weeks between them
    if ($min->format('w') !== "1") {
      // if we're not starting on monday, we need to skip forward to next monday
      $min->modify('next monday');
    }

    if ($max->format('w') !== "1") {
      // if we're not finishing on monday, we need to skip to the previous monday
      $max->modify('last monday');
    }

    $diff = $from->diff($to);

    // construct and send the response
    $response = new stdClass();
    $response->weeksDiff = $diff->days / 7;
    return $this->klein->response()->json($response);
  }
}
