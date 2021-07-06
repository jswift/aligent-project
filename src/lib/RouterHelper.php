<?php

/*
* A helper class containing useful router methods, exposing only the ones we want to use externally
*/
class RouterHelper {

  /*
  * Create and configure an instance of Klein router
  */
  public static function createKleinInstance() {
    // configure routing
    $app = new \Klein\App();
    $klein = new \Klein\Klein(null, $app);

    self::createValidators($klein);
    self::addErrorHandling($klein);
    self::addRoutes($klein);

    return $klein;
  }

  /*
  * Load validators into router
  */
  private static function createValidators(Klein\Klein $klein) {

    $validators = glob(__DIR__."/validators/*Validator.php");
    foreach($validators as $validator) {
      $fullClassName = substr(basename($validator), 0, -strlen(".php"));
      $kleinValidatorName = substr(basename($validator), 0, -strlen("Validator.php"));

      $klein->service()->addValidator($kleinValidatorName, function($v) use ($fullClassName) {
        return $fullClassName::validate(...func_get_args());
      });
    }

    foreach($validators as $validator) {
      $withoutValidatorSuffix = substr($validator, 0, -strlen("validator"));
      $klein->service()->addValidator($withoutValidatorSuffix, function($val) use ($validator) {
        return $validator::validate($val);
      });
    }
  }

  /*
  * Configure error handling in router
  */
  private static function addErrorHandling(Klein\Klein $klein) {

    // handle otherwise unhandled exceptions gracefully (eg. validation exceptions)
    $klein->onError(function ($klein, $message, $type, $err){

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

      // throw up developer safe information in development mode
      if (getenv("PHP_DEV") === "yes") {
        // TODO: make this a nice page throwing stack trace and everything
        // just a text dump showing the basic information works fine for now however...
        return $klein
          ->response()
          ->header("Content-Type", "text/plain")
          ->code(500)
          ->body("
  Exception message: '{$err->getMessage()}'
  file: {$err->getFile()}:{$err->getLine()}
          ")->send();
      }

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
  }

  /*
  * Load routes into the router
  */
  private static function addRoutes(Klein\Klein $klein) {

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

  }
}
