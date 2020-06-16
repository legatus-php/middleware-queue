<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\MiddlewareQueue\Tests;

use Legatus\Http\MiddlewareQueue\EmptyQueueException;
use Legatus\Http\MiddlewareQueue\Queue;
use Legatus\Http\MiddlewareQueue\QueueMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class QueueMiddlewareTest.
 */
class QueueMiddlewareTest extends TestCase
{
    public function testItHandlesRequest(): void
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $response = $this->createStub(ResponseInterface::class);

        $queue = $this->createMock(Queue::class);
        $queue->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $middleware = new QueueMiddleware($queue);

        $this->assertSame($response, $middleware->handle($request));
    }

    public function testItProcessRequestAndHandler(): void
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $response = $this->createStub(ResponseInterface::class);

        $queue = $this->createMock(Queue::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $queue->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);
        $handler->expects($this->never())
            ->method('handle')
            ->with($request);

        $middleware = new QueueMiddleware($queue);

        $this->assertSame($response, $middleware->process($request, $handler));
    }

    public function testDefaultsToFallbackHandler(): void
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $response = $this->createStub(ResponseInterface::class);

        $queue = $this->createMock(Queue::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $queue->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willThrowException(new EmptyQueueException('The queue is empty'));
        $handler->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $middleware = new QueueMiddleware($queue);

        $this->assertSame($response, $middleware->process($request, $handler));
    }

    public function testItPushesMiddleware(): void
    {
        $middlewareStub = $this->createStub(MiddlewareInterface::class);
        $queue = $this->createMock(Queue::class);
        $queue->expects($this->once())
            ->method('push')
            ->with($middlewareStub);

        $middleware = new QueueMiddleware($queue);
        $middleware->push($middlewareStub);
    }
}
