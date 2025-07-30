<?php

namespace App\Models;

use Core\Database;
use PDOException;
use DateTime;
use DateTimeZone;

class Coupon
{
  public static function find(string $id): ?array
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM coupons WHERE id like ?");
    $stmt->execute([$id]);

    return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
  }

  public static function all(): array
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM coupons");
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public static function create(string $code, float $discount, float $minimum_amount, string $expires_at): int
  {
    $db = Database::getInstance();
    try {
      $db->beginTransaction();

      $stmt = $db->prepare("INSERT INTO coupons (code, discount, minimum_amount, expires_at) VALUES (?, ?, ?, ?)");
      $stmt->execute([$code, $discount, $minimum_amount, $expires_at]);

      $db->commit();

      return (int) $db->lastInsertId();
    } catch (PDOException $e) {
      if ($db->inTransaction()) {
        $db->rollBack();
      }
      throw $e;
    }
  }

  public static function update($id, $code, $discount, $minimum_amount, $expires_at): bool
  {
    $db = Database::getInstance();

    try {
      $db->beginTransaction();

      $stmt = $db->prepare("UPDATE coupons SET code = ?, discount = ?, minimum_amount = ?, expires_at = ? WHERE id = ?");
      $ok = $stmt->execute([$code, $discount, $minimum_amount, $expires_at, $id]);

      $db->commit();

      return $ok;
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

      $stmt = $db->prepare("DELETE FROM coupons WHERE id = ?");
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

  public static function applyDiscount(string $code, float $subtotal): float
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND expires_at >= CURDATE()");
    $stmt->execute([$code]);
    $coupon = $stmt->fetch(\PDO::FETCH_ASSOC);

    if (!$coupon || $subtotal < $coupon['minimum_amount']) {
      return 0;
    }

    $discount = $subtotal * ($coupon['discount'] / 100);
    return $discount;
  }
}
