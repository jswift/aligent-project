<?php
class DateCalculatorController extends BaseController {

  public function countDaysBetweenDates() {
    $service = $this->klein->service();

    $service->validateParam('from', 'Please enter a valid \'from\' field')->notNull();
    $service->validateParam('to', 'Please enter a valid \'to\' field')->notNull();
  }
}
