<?php
namespace Core;

class Router {
    protected $routes = [];
    protected $groupOptions = [];
    
    public function add($method, $uri, $action, $middleware = []) {
        // Apply group prefix if it exists
        if (!empty($this->groupOptions['prefix'])) {
            $uri = rtrim($this->groupOptions['prefix'], '/') . '/' . ltrim($uri, '/');
        }
    
        // Merge middleware from group options and individual route
        $middleware = array_merge($this->groupOptions['middleware'] ?? [], $middleware ?? []);
    
        // Add the route to the routes array
        $this->routes[] = compact('method', 'uri', 'action', 'middleware');
    }

    public function get($url, $action, $middleware=null) {
        $this->add('GET', $url, $action, $middleware);
    }

    public function post($url, $action, $middleware=null) {
        $this->add('POST', $url, $action, $middleware);
    }

    public function group($options, $callback) {
        $previousGroupOptions = $this->groupOptions;

        // Set group options like prefix or middleware
        $this->groupOptions = array_merge($this->groupOptions, $options);

        // Execute the callback, which will register routes within this group
        $callback($this);

        // Restore previous group options after the callback finishes
        $this->groupOptions = $previousGroupOptions;
    }

    public function dispatch($uri, $method)
    {
        // Strip out query parameters from the URI (anything after ?)
        $path = trim(parse_url($uri, PHP_URL_PATH), '/');

        foreach ($this->routes as $route) {
            // Remove leading and trailing slashes from the route URI as well
            $routeUri = trim($route['uri'], '/');

            // Generate a regex pattern from the route's URI, replacing dynamic segments with capture groups
            $routePattern = preg_replace('/\{(\w+)\}/', '(\w+)', $routeUri);
            
            // Add delimiters and ensure it matches the entire URI path (without query parameters)
            $routePattern = "#^" . $routePattern . "$#";

            // Check if the HTTP method and the route pattern match
            if ($route['method'] === $method && preg_match($routePattern, $path, $matches)) {
                // Apply global middleware, if any
                foreach (config('middleware.groups.web') as $middleware) {
                    if (class_exists($middleware)) {
                        $middlewareInstance = new $middleware;
                        $middlewareInstance->handle();
                    }
                }

                // Apply route-specific middleware, if any
                if (!empty($route['middleware'])) {
                    $this->applyMiddleware($route['middleware']);
                }

                // Extract parameters (ignore the full match in `$matches[0]`)
                $parameters = array_slice($matches, 1);

                // Handle controller-based routes or direct closures
                if (is_string($route['action']) && strpos($route['action'], '@') !== false) {
                    list($controller, $action) = explode('@', $route['action']);
                    $controller = "App\\Controllers\\{$controller}";
                    return (new $controller)->$action(...$parameters);
                } else {
                    return call_user_func_array($route['action'], $parameters);
                }
            }
        }

        // Return 404 response if no route matched
        http_response_code(404);
        echo "404 - Route not found";
    }





    protected function applyMiddleware($middleware) {
        $middlewareAliases = config ('middleware.aliases');
        foreach ((array)$middleware as $ware) {
            $middlewareClass = $middlewareAliases[$ware] ?? 'App\Middleware\\'.$ware;
            if(!class_exists($middlewareClass)) {
                throw new \Exception("Middleware class {$middlewareClass} not found.");
            }
            (new $middlewareClass)->handle();
        }
    }
}
