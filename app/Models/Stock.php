<?php

namespace App\Models;

use Core\Database;
use PDO;

class Stock
{
  public static function getQuantity(int $productId): ?int
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT quantity FROM stocks WHERE product_id = ?");
    $stmt->execute([$productId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? (int) $result['quantity'] : null;
  }

  public static function decrement(int $productId, int $quantity): bool
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE stocks SET quantity = quantity - ? WHERE product_id = ? AND quantity >= ?");
    return $stmt->execute([$quantity, $productId, $quantity]);
  }
}
