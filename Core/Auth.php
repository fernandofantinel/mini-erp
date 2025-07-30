<?php

namespace Core;

class Auth
{
  public static function check(): bool
  {
    return isset($_SESSION['auth']);
  }

  public static function user(): ?array
  {
    return $_SESSION['auth'] ?? null;
  }

  public static function role(): ?string
  {
    return self::check() ? $_SESSION['auth']['role'] ?? null : null;
  }

  public static function isAdmin(): bool
  {
    return self::role() === 'admin';
  }

  public static function isUser(): bool
  {
    return self::role() === 'user';
  }

  public static function requireLogin(): void
  {
    if (!self::check()) {
      header('Location: /login');
      exit;
    }
  }

  public static function requireAdmin(): void
  {
    if (!self::isAdmin()) {
      header('Location: /');
      exit;
    }
  }
}
