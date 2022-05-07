<?php
declare(strict_types=1);

namespace Forme\Framework\Http\Handlers;

use \AltoRouter;
use Forme\Framework\Http\ResponseFactory;
use Forme\Framework\Http\Shutdown;

/**
 * This is based on Upstatement Routes
 * But refactored it to make it not static
 * and also less weird
 * e.g it was using globals unnecessarily
 * and the presence of a class as a matched flag
 * ain't nobody got time for that.
 */
class CustomRouteHandler implements HandlerInterface
{
    use HasMiddleware;

    /** @var bool */
    protected $matched = false;

    public function __construct(protected AltoRouter $router, private Shutdown $shutdown)
    {
        $this->initRouter();
        // TODO possibly move this from here, feels weird.
        add_action('wp_loaded', $this);
    }

    public function __invoke(): void
    {
        if (!$this->matched) {
            $route = $this->router->match();
            if ($route && isset($route['target'])) {
                $this->matched = true;
                // We call this here because things like admin bar rely on this hook
                // but TODO: test for side-effects?
                do_action('template_redirect');
                $request     = \Forme\request();
                if (isset($route['params'])) {
                    $request = $request->withQueryParams($route['params'] + $request->getQueryParams());
                }

                // we pass the response closure into the dispatcher
                $responseFunc = function ($request, $handler) use ($route) {
                    $response = call_user_func($route['target'], $request);

                    return ResponseFactory::create($response);
                };
                $response       = $this->dispatchMiddleware($request, $responseFunc);
                $this->shutdown->shutdown($response);
            }
        }
    }

    /**
     * @param string   $route    A string to match (ex: 'myfoo')
     * @param callable $callback A function to run, examples:
     *                           Routes::map('myfoo', 'my_callback_function');
     *                           Routes::map('mybaq', array($my_class, 'method'));
     *                           Routes::map('myqux', function() {
     *                           //stuff goes here
     *                           });
     */
    public function map(string $route, callable $callback, string $method = 'GET', string $args = ''): void
    {
        $route = $this->convertRoute($route);
        $this->router->map($method, trailingslashit($route), $callback, $args);
        $this->router->map($method, untrailingslashit($route), $callback, $args);
    }

    /**
     * @return string A string in a format for AltoRouter
     *                ex: [:my_param]
     */
    private function convertRoute(string $routeString): string
    {
        if (strpos($routeString, '[') > -1) {
            return $routeString;
        }

        $routeString = preg_replace('#(:)\w+#', '/[$0]', $routeString);
        $routeString = str_replace('[[', '[', $routeString);
        $routeString = str_replace(']]', ']', $routeString);
        $routeString = str_replace('[/:', '[:', $routeString);
        $routeString = str_replace('//[', '/[', $routeString);
        if (str_starts_with($routeString, '/')) {
            $routeString = substr($routeString, 1);
        }

        return $routeString;
    }

    private function initRouter(): void
    {
        // TODO move this, also feels wrong
        $siteUrl      = get_bloginfo('url');
        $siteUrlParts = explode('/', $siteUrl);
        $siteUrlParts = array_slice($siteUrlParts, 3);

        $basePath     = implode('/', $siteUrlParts);
        if ($basePath === '' || $basePath === '0') {
            $basePath = '/';
        } else {
            $basePath = '/' . $basePath . '/';
        }

        // Clean any double slashes that have resulted
        $basePath = str_replace('//', '/', $basePath);
        $this->router->setBasePath($basePath);
    }
}
