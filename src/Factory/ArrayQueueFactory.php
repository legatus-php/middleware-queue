<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\MiddlewareQueue\Factory;

use Legatus\Http\MiddlewareQueue\ArrayQueue;

/**
 * Class ArrayQueueFactory.
 */
final class ArrayQueueFactory implements QueueFactory
{
    /**
     * {@inheritdoc}
     */
    public function create(): ArrayQueue
    {
        return new ArrayQueue();
    }
}
