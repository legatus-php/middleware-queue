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
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class OutputMiddleware.
 */
class OutputMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private string $message;

    /**
     * OutputMiddleware constructor.
     *
     * @param string $message
     */
    public function __construct(string $message = 'output')
    {
        $this->message = $message;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        echo 'in-'.$this->message.PHP_EOL;
        $response = $handler->handle($request);
        echo 'out-'.$this->message.PHP_EOL;

        return $response;
    }
}
