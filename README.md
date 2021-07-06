# Dependencies
* docker
* docker-compose

# How do i run this?
1. clone the repo: `git clone git@github.com:mattftw/aligent-project.git`
2. run `docker-compose up` in the newly created `aligent-project` folder.

# How do i run unit tests?
1. run `make test`

# CURL examples
CURL examples are [here](/src/CURL-EXAMPLES.md)

# Information
* All dates used by the API must conform to the ISO8601 format. Example: `2021-07-30T09:30:10+00:00`
* Enable a debug mode by specifying `PHP_DEV=yes` environment variable to the `fpm` container

# Assumptions made
1. `/api/count_days_between_dates` endpoint doesnt need day / minute / second granularity.
2. `xUnit` wont really integrate well with PHP. I will choose a different unit testing framework (phpUnit).
3. Each item in the challange is a new endpoint (where applicable) instead of a single monolithic endpoint.
4. Adding of `days` and `weeks` into challenge #4
5. The default output denomination should be `day`
6. Timezones are handled by the use of `ISO1801` timestamps -- no changes are necessary for step #5
7. Added the 'third parameter' to all endpoints for consistency

# TODO (wont actually do, but some things that should be considered before putting into production)
* Use a proper templating engine like twig, for example
* Present the API docs in a nicer format displaying extra information such as
  * Expectable exceptions
    * validation error(s)
  * Success response example
  * URL / method / parameters all within a nice table
* change the error response structure to something like:
```json
{
  "validationErrors": {
    "field1": {
      "message": "field1 is required",
      "code": "REQUIRED_FIELD"
    }
  }
}
```
* global `set_error_handler` and reporting
* global `set_exception_handler` and reporting
* specify a more in-depth unhandled exception information page when in debug mode
* investigate possible performance improvements that can be made (if we can remove the need to interate over dates and use fancy maths instead, that would be nice)
