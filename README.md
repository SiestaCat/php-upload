Run mongodb in local: `docker run -p 27017:27017 mongo:latest`

Request upload token:

```
curl -X GET 'http://localhost:8000/api/request' \
  -H 'Authorization: Basic changeme' \
  -H 'Content-Type: application/json'  \
  --compressed -L
```

Upload files:

```
curl 'http://localhost:8000/upload/8Q9skEQCROEc4WK36adc7zmeYZXJGFGlz9unw7zBw8PeSUyLXn5NxAt4bP5tNxGo' \
  -F 'files[]=@composer.json' \
  -F 'files[]=@composer.lock' \
  --compressed -L
```