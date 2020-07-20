<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Class ArrayMiddlewareQueue.
 */
final class ArrayMiddlewareQueue implements MiddlewareQueue
{
    /**
     * @var MiddlewareInterface[]
     */
    private array $queue;

    /**
     * ArrayMiddlewareQueue constructor.
     *
     * @param MiddlewareInterface ...$queue
     */
    public function __construct(MiddlewareInterface ...$queue)
    {
        $this->queue = $queue;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): MiddlewareInterface
    {
        $element = array_shift($this->queue);
        if ($element instanceof MiddlewareInterface) {
            return $element;
        }
        throw new \OutOfBoundsException('The queue is empty');
    }

    /**
     * {@inheritdoc}
     */
    public function copy(): MiddlewareQueue
    {
        $clone = clone $this;
        $clone->queue = [];

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function push(MiddlewareInterface $middleware): void
    {
        $this->queue[] = $middleware;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $clone = clone $this;
        $middleware = $clone->next();

        return $middleware->process($request, $clone);
    }
}
