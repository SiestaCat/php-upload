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
curl -X POST 'http://localhost:8000/upload/oqNrpZA53szywC6bhbYEFEjZwqFZYnJRGyZq0sMb1nFJ5SMPcw1qBDP1F4NsRkJv' \
  -F 'files[]=@composer.json' \
  -F 'files[]=@composer.lock' \
  --compressed -L
```

Get files:

```
curl -X GET 'http://localhost:8000/api/files/oqNrpZA53szywC6bhbYEFEjZwqFZYnJRGyZq0sMb1nFJ5SMPcw1qBDP1F4NsRkJv' \
  -H 'Authorization: Basic changeme' \
  -H 'Content-Type: application/json'  \
  --compressed -L
```

Download single file:

```
curl -X GET 'http://localhost:8000/api/download/oqNrpZA53szywC6bhbYEFEjZwqFZYnJRGyZq0sMb1nFJ5SMPcw1qBDP1F4NsRkJv/4ec879f02769878f999425367bf5a2eaf0bdb7916b56a92a3be6cf5aa72add5217b5309f876c5c7f89dd4e9250c922479572da5e0c5edc654b8778b6c6dde69f' \
  -H 'Authorization: Basic changeme' \
  --compressed -L
```