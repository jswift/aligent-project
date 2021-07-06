<?php

class IndexController extends BaseController {
  public function handle() {

    $parsedown = new Parsedown();
    $content = $parsedown->text(file_get_contents(__DIR__.'/../CURL-EXAMPLES.md'));
    $htmlBody = "
<html>
  <head>
    <title>Aligent Project</title>
    <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css\" integrity=\"sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T\" crossorigin=\"anonymous\">
  </head>
  <body>
    <div class=\"container\">
      <div class=\"row\">
        <div class=\"col-md-12\">
          <h1>Aligent Project</h1>
          <hr/>
          $content
        </div>
      </div>
    </div>
  </body>
</html>
    ";


    return $this
      ->klein
      ->response()
      ->body($htmlBody);
  }
}
