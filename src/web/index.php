<?php
require_once __DIR__.'/../vendor/autoload.php';

// call_user_func used so a variable is not created for use in other places
call_user_func(function() {

  // configure routing
  $app = new \Klein\App();
  $klein = new \Klein\Klein(null, $app);

  // iterate routing.yml to populate the klein router
  $routes = yaml_parse_file(__DIR__.'/../config/routing.yml');
  foreach($routes as $routeData) {

    // create a entry in the router based off the contents of routing.yml
    $klein->respond($routeData['methods'], $routeData['route'], function ($request, $response, $service, $app) use($klein, $routeData) {

      // create an instance of the controller
      $ref = new ReflectionClass($routeData['class']);
      $controller = $ref->newInstanceArgs([$klein]);

      // use the user specified entrypoint to the controller
      $entrypointMethod = array_key_exists('entrypoint', $routeData) ? $routeData['entrypoint'] : 'handle';

      // call the specified (or default) controller entrypoint
      return call_user_func([$controller, $entrypointMethod]);
    });
  }

  // handle the routes
  $klein->dispatch();
});
