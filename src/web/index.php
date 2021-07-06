<?php
require_once __DIR__.'/../vendor/autoload.php';

// call_user_func used so a variable is not created for use in other places
call_user_func(function() {

  // create a router
  $klein = RouterHelper::createKleinInstance();

  // handle the routes
  $klein->dispatch();
});
