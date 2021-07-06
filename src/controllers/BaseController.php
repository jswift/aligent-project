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
  }
}
