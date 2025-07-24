const http = require('http');

const server = http.createServer((req, res) => {
  // Trả lời cực nhanh
  res.writeHead(200, { 'Content-Type': 'text/plain' });
  res.end('Hello from Node.js\n');
});

const PORT = 8885;
const HOST = '0.0.0.0';

server.listen(PORT, HOST, () => {
  console.log(`Server running at http://${HOST}:${PORT}/`);
});