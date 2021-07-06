# Dependencies
* docker
* docker-compose

# How do i run this?
1. clone the repo: `git clone git@github.com:mattftw/aligent-project.git`
2. run `docker-compose up` in the newly newly created `aligent-project` folder.

# How do i run unit tests?
they dont exist yet

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
* investigate possible performance improvements that can be made (if we can remove the need to interate over dates and use fancy maths instead, that would be nice)
