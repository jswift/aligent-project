<?php
class DateCalculatorController extends BaseController {

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
}
