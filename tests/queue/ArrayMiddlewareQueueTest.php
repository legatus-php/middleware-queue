<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ArrayMiddlewareQueueTest.
 */
class ArrayMiddlewareQueueTest extends TestCase
{
    public function testItRuns(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $request = $this->createStub(ServerRequestInterface::class);

        $queue = new ArrayMiddlewareQueue(new OutputMiddleware('1'), new OutputMiddleware('2'), new OutputMiddleware('3'));
        $queue->push(new ResponseMiddleware($response));

        $this->expectOutputString(
            'in-1'.PHP_EOL.
            'in-2'.PHP_EOL.
            'in-3'.PHP_EOL.
            'out-3'.PHP_EOL.
            'out-2'.PHP_EOL.
            'out-1'.PHP_EOL
        );

        $this->assertSame($response, $queue->handle($request));
    }

    public function testItEmpties(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $request = $this->createStub(ServerRequestInterface::class);

        $queue = new ArrayMiddlewareQueue(new OutputMiddleware('1'), new OutputMiddleware('2'), new OutputMiddleware('3'));
        $queue->push(new ResponseMiddleware($response));

        $emptyQueue = $queue->copy();

        $this->expectOutputString(
            'in-1'.PHP_EOL.
            'in-2'.PHP_EOL.
            'in-3'.PHP_EOL.
            'out-3'.PHP_EOL.
            'out-2'.PHP_EOL.
            'out-1'.PHP_EOL
        );

        $this->assertSame($response, $queue->handle($request));
        $this->expectException(OutOfBoundsException::class);
        $emptyQueue->handle($request);
    }

    public function testItRunsTwiceWithNoStateLoss(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $request = $this->createStub(ServerRequestInterface::class);

        $queue = new ArrayMiddlewareQueue(new OutputMiddleware('1'), new OutputMiddleware('2'), new OutputMiddleware('3'));
        $queue->push(new ResponseMiddleware($response));

        $this->expectOutputString(
            'in-1'.PHP_EOL.
            'in-2'.PHP_EOL.
            'in-3'.PHP_EOL.
            'out-3'.PHP_EOL.
            'out-2'.PHP_EOL.
            'out-1'.PHP_EOL.
            'in-1'.PHP_EOL.
            'in-2'.PHP_EOL.
            'in-3'.PHP_EOL.
            'out-3'.PHP_EOL.
            'out-2'.PHP_EOL.
            'out-1'.PHP_EOL
        );

        $this->assertSame($response, $queue->handle($request));
        $this->assertSame($response, $queue->handle($request));
    }

    public function testItThrowsErrorOnEmptyQueue(): void
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $queue = new ArrayMiddlewareQueue();
        $this->expectException(OutOfBoundsException::class);
        $queue->handle($request);
    }
}
