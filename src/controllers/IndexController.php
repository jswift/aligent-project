<?php

class IndexController extends BaseController{
  public function handle() {
    return $this
      ->klein
      ->response()
      ->header("Content-Type", "text/plain")
      ->body("foo");
  }
}
