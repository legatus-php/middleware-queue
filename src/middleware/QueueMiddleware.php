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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class QueueMiddleware.
 */
class QueueMiddleware implements MiddlewareInterface, RequestHandlerInterface
{
    protected MiddlewareQueue $queue;

    /**
     * QueueMiddleware constructor.
     *
     * @param MiddlewareQueue $queue
     */
    public function __construct(MiddlewareQueue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param MiddlewareInterface $middleware
     */
    public function push(MiddlewareInterface $middleware): void
    {
        $this->queue->push($middleware);
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $this->handle($request);
        } catch (OutOfBoundsException $exception) {
            return $handler->handle($request);
        }
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws OutOfBoundsException when the queue is exhausted
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->queue->handle($request);
    }
}
