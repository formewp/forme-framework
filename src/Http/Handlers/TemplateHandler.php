<?php
declare(strict_types=1);

namespace Forme\Framework\Http\Handlers;

use function Forme\arrayKeysToCamelCase;
use Forme\Framework\Controllers\ControllerInterface;
use Forme\Framework\Http\ResponseFactory;
use Forme\Framework\Http\Shutdown;
use Psr\Container\ContainerInterface;
use function Symfony\Component\String\u;

class TemplateHandler implements HandlerInterface
{
    use HasMiddleware;

    /** @var ContainerInterface */
    private $container;

    /** @var Shutdown */
    private $shutdown;

    public function __construct(ContainerInterface $container, Shutdown $shutdown)
    {
        $this->container           = $container;
        $this->shutdown            = $shutdown;
    }

    /** @return void|null */
    public function __invoke(?string $template)
    {
        if (!$template) {
            return null;
        }
        include $template;

        $controller = $this->getControllerClassFromTemplate($template);

        return $this->handleRequest($controller);
    }

    private function getControllerClassFromTemplate(string $template): string
    {
        $controllerName = u(basename($template, '.php'))->camel()->title()->toString();
        // if the name doesn't end with Controller then add 'Controller'
        $controllerName .= str_ends_with($controllerName, 'Controller') ? '' : 'Controller';
        // Classes can't start with a number so we have to special case the behaviour here
        if ($controllerName === '404Controller') {
            $controllerName = 'Error' . $controllerName;
        }

        return $this->getNameSpace($template) . '\\' . $controllerName;
    }

    /** @return void|null */
    private function handleRequest(string $controllerName)
    {
        if (!class_exists($controllerName)) {
            return null;
        }
        /** @var ControllerInterface */
        $controller  = $this->container->get($controllerName);
        $this->addMiddlewareQueue($controller->getMiddlewareQueue());

        $postId      = get_queried_object_id();
        $fields      = get_fields($postId);
        $options     = get_fields('options');
        $request     = \Forme\request();
        $request     = $request->withParsedBody([
            'postId'  => $postId,
            'fields'  => arrayKeysToCamelCase($fields),
            'options' => arrayKeysToCamelCase($options),
        ]);
        // we pass the response closure into the dispatcher
        $responseFunc = function ($request, $handler) use ($controller) {
            $response   = $controller->handle($request);

            return ResponseFactory::create($response);
        };
        $response       = $this->dispatchMiddleware($request, $responseFunc);
        $this->shutdown->shutdown($response);
    }

    /**
     * Extract the namespace from file contents.
     */
    private function getNameSpace(string $file): ?string
    {
        $fileContents = file_get_contents($file);
        if (preg_match('#^namespace\s+(.+?);$#sm', $fileContents, $m)) {
            return $m[1];
        }

        return null;
    }
}
