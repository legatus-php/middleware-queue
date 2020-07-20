Legatus Middleware Queue
========================

A middleware queue that handles requests and works as a middleware itself

[![Build Status](https://drone.mnavarro.dev/api/badges/legatus/middleware-queue/status.svg)](https://drone.mnavarro.dev/legatus/middleware-queue)

## Installation
You can install the Middleware Queue component using [Composer][composer]:

```bash
composer require legatus/middleware-queue
```

## Quick Start

```php
<?php

$queue = new Legatus\Http\ArrayMiddlewareQueue();
$queue->push(new SomeMiddleware());
$queue->push(new SomeOtherMiddleware());

$queue->handle($request);

// Or use the queue as a middleware
$queueMiddleware = new Legatus\Http\QueueMiddleware($queue);

$queueMiddleware->process($request, $handler);
```

For more details you can check the [online documentation here][docs].

## Community
We still do not have a community channel. If you would like to help with that, you can let me know!

## Contributing
Read the contributing guide to know how can you contribute to Quilt.

## Security Issues
Please report security issues privately by email and give us a period of grace before disclosing.

## About Legatus
Legatus is a personal open source project led by Matías Navarro Carter and developed by contributors.

[composer]: https://getcomposer.org/
[docs]: https://legatus.mnavarro.dev/components/middleware-queue