<?php

namespace Core;

class Router
{
  private array $routes = [];

  public function add(string $method, string $uri, callable $action): void
  {
    $this->routes[strtoupper($method)][$uri] = $action;
  }

  public function addRegex(string $method, string $pattern, callable $action): void
  {
    $this->routes[strtoupper($method)]['@regex'][] = ['pattern' => $pattern, 'action' => $action];
  }

  public function dispatch(string $method, string $uri): void
  {
    $method = strtoupper($method);
    $uri = rtrim($uri, '/') ?: '/';

    if (isset($this->routes[$method][$uri])) {
      $this->routes[$method][$uri]();
      return;
    }

    foreach ($this->routes[$method]['@regex'] ?? [] as $route) {
      if (preg_match($route['pattern'], $uri, $matches)) {
        array_shift($matches);
        $route['action'](...$matches);
        return;
      }
    }

    http_response_code(404);
    View::render('404');
  }
}
