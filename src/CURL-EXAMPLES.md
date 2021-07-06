**Determine the number of days between two datetime parameters**
----

```bash
curl --location --request POST 'http://localhost:8080/api/count_days_between_dates' \
--header 'Content-Type: application/json' \
--data-raw '{
    "from": "2021-07-06T09:34:30+00:00",
    "to": "2021-07-30T09:30:10+00:00",
    "denomination": "day"
}'
```

**Determine the number of weekdays between two datetime parameters**
----

```bash
curl --location --request POST 'http://localhost:8080/api/count_weekdays_between_dates' \
--header 'Content-Type: application/json' \
--data-raw '{
    "from": "2020-07-05T09:34:30+00:00",
    "to": "2021-07-30T09:30:10+00:00",
    "denomination": "day"
}'
```

**Determine the number of full weeks between two datetime parameters**
----

```bash
curl --location --request POST 'http://localhost:8080/api/count_weeks_bettween_dates' \
--header 'Content-Type: application/json' \
--data-raw '{
    "from": "2020-07-05T09:34:30+00:00",
    "to": "2021-07-30T09:30:10+00:00",
    "denomination": "week"
}'
```

