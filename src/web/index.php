<?php
require_once __DIR__.'/../vendor/autoload.php';

// call_user_func used so a variable is not created for use in other places
call_user_func(function() {

  // configure routing
  $app = new \Klein\App();
  $klein = new \Klein\Klein(null, $app);

  // handle otherwise unhandled exceptions gracefully (eg. validation exceptions)
  $klein->onError(function ($klein, $message, $type, Exception $err){

    // handle validation exceptions
    if ($err instanceof Klein\Exceptions\ValidationException) {
      $klein
        ->response()
        ->header("Content-Type", "application/json")
        ->code(422)
        ->json([
          'validationMessages' => [
            $message
          ]
        ])
        ->send();
    }

    // anything else will get handled and show a generic message
    // TODO: report on un-handleable errors
    return $klein
      ->response()
      ->header("Content-Type", "text/plain")
      ->code(500)
      ->body("an unexpected error has occoured")
      ->send();
  });

  // Handle http error codes... 404... 500... etc
  $klein->onHttpError(function ($code, $router) use ($klein) {

    if ($code === 404){
      $controller = new HTTP404Controller($klein);
      return $controller->handle();
    }
  });

  // iterate routing.yml to populate the klein router
  $routes = yaml_parse_file(__DIR__.'/../config/routing.yml');
  foreach($routes as $routeData) {

    // create a entry in the router based off the contents of routing.yml
    $klein->respond($routeData['methods'], $routeData['route'], function ($request, $response, $service, $app) use($klein, $routeData) {

      // merge in JSON post content manually since klein doesnt have default support for it
      if ($request->headers()->get('Content-Type') == 'application/json' && in_array($request->method(), ['POST'])) {
        $result = json_decode($request->body(), true);

        // perform basic validation of the request data
        if (json_last_error() === JSON_ERROR_NONE) {
          $request->paramsPost()->merge($result);
        } else {
          return $klein
            ->response()
            ->code(400)
            ->body("bad request. Please ensure JSON is valid");
        }
      }

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
