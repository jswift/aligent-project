<?php
abstract class BaseController {

  # The router instance
  protected $klein = null;
  public function __construct(\Klein\Klein $klein)
  {
    $this->klein = $klein;
  }

  /*
  * Handle the request
  */
  public function handle()
  {
    $className = get_class($this);

    return $this
      ->klein
      ->response()
      ->header("Content-Type", "text/plain")
      ->body("please create a handle() method in: '$className'");
  }
}
