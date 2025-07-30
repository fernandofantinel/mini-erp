<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
  private static ?PDO $instance = null;

  public static function getInstance(): PDO
  {
    if (self::$instance === null) {
      $config = require dirname(__DIR__, 1) . "/.env.php";

      $host   = $config["database"]['DB_HOST'] ?? 'localhost';
      $dbname = $config["database"]['DB_NAME'] ?? 'minierp';
      $user   = $config["database"]['DB_USER'] ?? 'user';
      $pass   = $config["database"]['DB_PASS'] ?? 'userpass';

      try {
        self::$instance = new PDO(
          "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
          $user,
          $pass,
          [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
          ]
        );
      } catch (PDOException $e) {
        die("❌ Conexão com banco de dados falhou: " . $e->getMessage());
      }
    }

    return self::$instance;
  }
}
