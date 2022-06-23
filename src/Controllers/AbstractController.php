<?php
declare(strict_types=1);

namespace Forme\Framework\Controllers;

use Forme\Framework\Http\Handlers\HasMiddleware;
use Forme\Framework\Http\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractController implements ControllerInterface
{
    use HasMiddleware;

    /**
     * @param array|ServerRequestInterface|ServerRequest $request
     *
     * @return mixed
     *
     * this method should send back relevant response to output
     * most often a string or an http response
     */
    abstract public function __invoke($request);

    /**
     * @param array|ServerRequestInterface|ServerRequest $request
     *
     * @return mixed
     *
     * this method should send back relevant response to output
     * most often a string or an http response
     */
    public function handle($request)
    {
        return $this->__invoke($request);
    }
}
