<?php

class HTTP404Controller extends BaseController {
  public function handle() {
    return $this
      ->klein
      ->response()
      ->header("Content-Type", "text/plain")
      ->body("the specified page does not exist");
  }
}
