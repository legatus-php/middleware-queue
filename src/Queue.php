<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\MiddlewareQueue;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface Queue.
 */
interface Queue extends RequestHandlerInterface
{
    /**
     * Fetches the next middleware in the queue.
     *
     * @return MiddlewareInterface
     *
     * @throws EmptyQueueException if the queue is empty
     */
    public function next(): MiddlewareInterface;

    /**
     * Pushes middleware onto the queue.
     *
     * @param MiddlewareInterface $middleware
     */
    public function push(MiddlewareInterface $middleware): void;

    /**
     * Empties the queue.
     *
     * Implementors MUST not mutate the original queue.
     *
     * @return Queue
     */
    public function empty(): Queue;

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface;
}
