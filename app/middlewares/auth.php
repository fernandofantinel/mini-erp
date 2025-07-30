<?php

use Core\Auth;

function middleware(string|array $name, callable $callback): callable
{
  $middlewares = is_array($name) ? $name : [$name];

  return function (...$args) use ($middlewares, $callback) {
    foreach ($middlewares as $mw) {
      match ($mw) {
        'auth' => Auth::requireLogin(),
        'admin' => Auth::requireAdmin(),
        default => null
      };
    }

    return $callback(...$args);
  };
}
