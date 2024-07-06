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

### HTTPS

The Vagrant box can serve requests via HTTP/2, however, at the time of writing, most browsers (Chrome, Firefox) only make HTTP/2 requests via HTTPS. An HTTPS compatible `VirtualHost` is set up when provisioning and [minica](https://github.com/jsha/minica) is used to generate a site certificate.

### Root CA certificate
To specify the root certificate, place `*.pem` files for the certificate and key in `localdev/secrets`

This, in conjunction with trusting the root certificate on the host, can be done to avoid browsers showing that the site certificate is invalid.

For more on this pattern see [https://semisignal.com/https-for-local-development/](https://semisignal.com/https-for-local-development/)
