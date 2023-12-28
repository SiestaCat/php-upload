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
curl -X POST 'http://localhost:8000/upload/DWBW1fQhX8XHAZcI2yTG3XDogYU68R5zd7G2B5WQrUElO7Z7EyEuK1wGK4VnAp8v' \
  -F 'files[]=@composer.json' \
  -F 'files[]=@composer.lock' \
  --compressed -L
```