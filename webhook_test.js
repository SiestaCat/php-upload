const http = require("http");

const port = 3000;

var status = 0;

const server = http.createServer((req, res) => {
    res.statusCode = 200;

    if(req.url === '/set')
    {
        status = 1;
        res.end('ok');
    }

    if(req.url === '/get')
    {
        res.end(status.toString());
        status = 0;
    }
});

server.listen(3000, null, () => {
  console.log(`Server running at http://localhost:${port}/`);
});