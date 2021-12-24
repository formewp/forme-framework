<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

use function Forme\arrayKeysToCamelCase;
use Forme\Framework\Controllers\ControllerInterface;
use Forme\Framework\Http\ResponseFactory;
use Forme\Framework\Http\Shutdown;
use Psr\Container\ContainerInterface;
use function Symfony\Component\String\u;

/** @deprecated now handled directly in Forme\Framework\Core\CoreHooks via Forme\Framework\Http\Handlers instead  */
class TemplateHandler
{
    /** @var string */
    private $controllerNameSpace;

    /** @var ContainerInterface */
    private $container;

    /** @var Shutdown */
    private $shutdown;

    public function __construct(ContainerInterface $container, Shutdown $shutdown)
    {
        $this->container           = $container;
        $this->controllerNameSpace = $this->getControllerNameSpace();
        $this->shutdown            = $shutdown;
    }

    /** @return void|null */
    public function handle(string $template)
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

        return $this->controllerNameSpace . $controllerName;
    }

    /** @return void|null */
    private function handleRequest(string $controllerName)
    {
        if (!class_exists($controllerName)) {
            return null;
        }
        /** @var ControllerInterface */
        $controller = $this->container->get($controllerName);
        $postId     = get_queried_object_id();
        $fields     = get_fields($postId);
        $options    = get_fields('options');
        $response   = $controller->handle([
            'postId'  => $postId,
            'fields'  => arrayKeysToCamelCase($fields),
            'options' => arrayKeysToCamelCase($options),
        ]);
        $response = ResponseFactory::create($response);
        $this->shutdown->shutdown($response);
    }

    private function getControllerNameSpace(): string
    {
        $reflector     = new \ReflectionClass(get_called_class());
        $coreNameSpace = $reflector->getNamespaceName();
        $exploded      = explode('\\', $coreNameSpace);
        array_pop($exploded);
        $imploded = implode('\\', $exploded);

        return $imploded . '\\Controllers\\';
    }
}
