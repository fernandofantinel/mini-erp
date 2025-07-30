<?php

namespace Core;

class View
{
  public static function render(string $view, array $data = []): void
  {
    extract($data);
    require dirname(__DIR__, 1) . "/app/Views/layouts/header.php";
    require dirname(__DIR__, 1) . "/app/Views/{$view}.php";
    require dirname(__DIR__, 1) . "/app/Views/layouts/footer.php";
  }
}
