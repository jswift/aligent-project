# Dependencies
* docker
* docker-compose

# How do i run this?
1. clone the repo: `git clone git@github.com:mattftw/aligent-project.git`
2. run `docker-compose up` in the newly created `aligent-project` folder.

# How do i run unit tests?
they dont exist yet

# Information
Specify a debug mode by specifying `PHP_DEV` mode to the `fpm` container

# Assumptions made
1. `/count_days_between_dates` endpoint doesnt need to return days / minutes / seconds.
2. `xUnit` wont really integrate well with PHP. I will choose a different unit testing framework.
3. Each item in the challange is a new endpoint (where applicable) instead of a single monolithic endpoint.
4. adding of `days` and `weeks` into challenge #4
5. the default output denomination should be `day`
6. Timezones are handled by the use of `ISO1801` timestamps -- no changes are necessary for step #5
7. Added the 'third parameter' to all endpoints for consistency

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
