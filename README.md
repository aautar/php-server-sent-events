# php-server-sent-events

Examples of serving [server-sent events](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events) via PHP.

[SlimPHP](https://www.slimframework.com/) is used for routing and request/response handling.

## Running with PHP dev server
```bash
php -S localhost:8080 -t public
```

## Running with Vagrant box apache2 server
```bash
vagrant up
```

A vagrant box is provided which will provision [Apache](https://httpd.apache.org/) for serving requests. This is more powerful and flexible than the PHP dev server and doesn't have some of the annoying limitations (e.g. can only serve a single request at a time).
