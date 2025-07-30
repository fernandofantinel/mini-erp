<?php

namespace App\Models;

use Core\Database;
use PDO;
use PDOException;

class Login
{
  public static function login(string $email): ?array
  {
    $db = Database::getInstance();

    try {
      $db->beginTransaction();

      $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->execute([$email]);

      $db->commit();

      return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
      if ($db->inTransaction()) {
        $db->rollBack();
      }
      throw $e;
    }
  }

  public static function register(string $name, string $email, string $password): void
  {
    $db = Database::getInstance();

    try {
      $db->beginTransaction();

      $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
      $stmt->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT)]);

      $db->commit();

      return;
    } catch (PDOException $e) {
      if ($db->inTransaction()) {
        $db->rollBack();
      }
      throw $e;
    }
  }
}
