<?php

namespace IdNet\Middleware;

use FastRoute\Dispatcher;
use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Invoker\InvokerInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouterMiddleware implements ServerMiddlewareInterface
{
    /** @var Dispatcher */
    protected $router;

    /** @var InvokerInterface */
    protected $invoker;

    /** @var ResponseFactoryInterface */
    protected $responseFactory;

    public function __construct(
        Dispatcher $router,
        InvokerInterface $invoker,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->router = $router;
        $this->invoker = $invoker;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $route = $this->router->dispatch($request->getMethod(), $request->getUri()->getPath());

        if ($route[0] === Dispatcher::NOT_FOUND)
            return $this->responseFactory->createResponse(404);

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED)
            return $this->responseFactory->createResponse(405);

        $handler = $route[1];
        $vars = $route[2];

        return $this->invoker->call($handler, $vars + [
            'request' => $request,
        ]);
    }

}
