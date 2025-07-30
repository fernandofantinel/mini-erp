<?php

namespace App\Models;

use Core\Database;
use PDO;
use PDOException;

class Product
{
  public static function find(int $id): ?array
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("
      SELECT 
          p.id,
          p.name,
          p.price,
          p.variation,
          s.quantity
      FROM products p
      LEFT JOIN stocks s ON s.product_id = p.id
      WHERE p.id = ?");
    $stmt->execute([$id]);

    return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
  }

  public static function all(): array
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("
        SELECT 
            p.id,
            p.name,
            p.price,
            p.variation,
            s.quantity
        FROM products p
        LEFT JOIN stocks s ON s.product_id = p.id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public static function create(string $name, float $price, string $variation, int $quantity): int
  {
    $db = Database::getInstance();
    try {
      $db->beginTransaction();

      $stmt = $db->prepare("INSERT INTO products (name, price, variation) VALUES (?, ?, ?)");
      $stmt->execute([$name, $price, $variation]);

      $productId = (int) $db->lastInsertId();

      $stmt2 = $db->prepare("INSERT INTO stocks (product_id, quantity) VALUES (?, ?)");
      $stmt2->execute([$productId, $quantity]);

      $db->commit();

      return $productId;
    } catch (PDOException $e) {
      if ($db->inTransaction()) {
        $db->rollBack();
      }
      throw $e;
    }
  }

  public static function update(int $productId, string $name, float $price, string $variation, int $quantity): bool
  {
    $db = Database::getInstance();

    try {
      $db->beginTransaction();

      $stmt1 = $db->prepare("UPDATE products SET name = ?, price = ?, variation = ? WHERE id = ?");
      $ok1 = $stmt1->execute([$name, $price, $variation, $productId]);

      $stmt2 = $db->prepare("UPDATE stocks SET quantity = ? WHERE product_id = ?");
      $ok2 = $stmt2->execute([$quantity, $productId]);

      $db->commit();

      return $ok1 && $ok2;
    } catch (PDOException $e) {
      if ($db->inTransaction()) {
        $db->rollBack();
      }
      throw $e;
    }
  }

  public static function delete(int $id): void
  {
    $db = Database::getInstance();

    try {
      $db->beginTransaction();

      $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
      $stmt->execute([$id]);

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
