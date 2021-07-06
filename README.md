# Information
Specify a debug mode by specifying `PHP_DEV` mode to the `fpm` container

# TODO
* The rest of the readme
* change the error response structure to something like:
```json
{
  "validationErrors": {
    "field1": {
      "message": "field1 is required"
    }
  }
}
```
* global `set_error_handler` and reporting
* global `set_exception_handler` and reporting
* specify a more in-depth unhandled exception information page when in debug mode
