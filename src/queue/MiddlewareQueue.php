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
 * Interface MiddlewareQueue.
 *
 * Defines the contract for a queue of PSR-7 middleware
 */
interface MiddlewareQueue extends RequestHandlerInterface
{
    /**
     * Fetches the next middleware in the queue.
     *
     * @return MiddlewareInterface
     *
     * @throws OutOfBoundsException if the queue is empty
     */
    public function next(): MiddlewareInterface;

    /**
     * Pushes middleware onto the queue.
     *
     * @param MiddlewareInterface $middleware
     */
    public function push(MiddlewareInterface $middleware): void;

    /**
     * Copies the queue with a clean state.
     *
     * This method returns a new queue instance.
     *
     * @return MiddlewareQueue
     */
    public function copy(): MiddlewareQueue;

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws OutOfBoundsException when the queue is exhausted
     */
    public function handle(ServerRequestInterface $request): ResponseInterface;
}
