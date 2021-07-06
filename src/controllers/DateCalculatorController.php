<?php
class DateCalculatorController extends BaseController {

  /*
  * API endpoint to count the days between two dates
  */
  public function countDaysBetweenDates() {
    $service = $this->klein->service();
    $request = $this->klein->request();

    // perform some date format validation
    $service->validateParam('from', 'Please enter a valid \'from\' field')
      ->notNull()
      ->isIso8601Date();

    $service->validateParam('to', 'Please enter a valid \'to\' field')
      ->notNull()
      ->isIso8601Date();

    $this->addDenominationParameterIfNotExists();

    // convert the string to a date object
    $from = DateTime::createFromFormat(DATE_ISO8601, $request->from);
    $to = DateTime::createFromFormat(DATE_ISO8601, $request->to);

    // calculate the diff between the two dates
    $diff = $from->diff($to);

    // construct and send the response
    $response = new stdClass();
    $response->diff = TimeDenomination::convertBetween($diff->days, TimeDenomination::DAY, $request->denomination);
    $response->diffDenomination = $request->denomination;
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
    $request = $this->klein->request();

    // perform some date format validation
    $service->validateParam('from', 'Please enter a valid \'from\' field')
      ->notNull()
      ->isIso8601Date();

    $service->validateParam('to', 'Please enter a valid \'to\' field')
      ->notNull()
      ->isIso8601Date();

    $this->addDenominationParameterIfNotExists();

    // convert the string to a date object
    $from = DateTime::createFromFormat(DATE_ISO8601, $request->from);
    $to = DateTime::createFromFormat(DATE_ISO8601, $request->to);

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
    $response->weekdaysDiff = TimeDenomination::convertBetween($weekdays, TimeDenomination::DAY, $request->denomination);
    $response->weekdaysDenomination = $request->denomination;;
    return $this->klein->response()->json($response);
  }

  /*
  * API endpoint to determine the amount of full weeks between two dates
  */
  public function countWeeksBetweenDates() {
    $service = $this->klein->service();
    $request = $this->klein->request();

    // perform some date format validation
    $service->validateParam('from', 'Please enter a valid \'from\' field')
      ->notNull()
      ->isIso8601Date();

    $service->validateParam('to', 'Please enter a valid \'to\' field')
      ->notNull()
      ->isIso8601Date();

    $this->addDenominationParameterIfNotExists();

    // convert the string to a date object
    $from = DateTime::createFromFormat(DATE_ISO8601, $request->from);
    $to = DateTime::createFromFormat(DATE_ISO8601, $request->to);

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
    $response->weekdaysDiff = TimeDenomination::convertBetween($diff->days, TimeDenomination::DAY, $request->denomination);
    $response->weekdaysDenomination = $request->denomination;;
    return $this->klein->response()->json($response);
  }

  /*
  * Add the denomination parameter to the request if it doesnt exist
  * Specify a sensible default
  */
  private function addDenominationParameterIfNotExists() {
    $service = $this->klein->service();
    $request = $this->klein->request();

    if ($request->denomination) {
      $service->validateParam('denomination', "please enter a valid denomination")
      ->isTimeDenomination();
    } else {
      $request->paramsPost()->merge([
        'denomination' => TimeDenomination::DAY
      ]);
    }
  }
}
