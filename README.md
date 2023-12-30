Run mongodb in local: `docker run -p 27017:27017 mongo:latest`

Run tests: `php bin/phpunit`

Request upload token:

```
curl -X GET 'http://localhost:8000/api/request' \
  -H 'Authorization: Basic changeme' \
  -H 'Content-Type: application/json'  \
  --compressed -L
```

Upload files:

```
curl -X POST 'http://localhost:8000/upload/OP5hzlHbZ3HmACA4SI3SivnRl9sLe6eEQHcwFX0u1m9hPgfgY5OolQnRkoRsb0S0' \
  -F 'files[]=@composer.json' \
  -F 'files[]=@composer.lock' \
  --compressed -L
```

Get files:

```
curl -X GET 'http://localhost:8000/api/files/OP5hzlHbZ3HmACA4SI3SivnRl9sLe6eEQHcwFX0u1m9hPgfgY5OolQnRkoRsb0S0' \
  -H 'Authorization: Basic changeme' \
  -H 'Content-Type: application/json'  \
  --compressed -L
```