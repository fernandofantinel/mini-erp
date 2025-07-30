<?php

namespace App\Models;

use App\Models\Stock;
use Core\Database;
use PDO;
use PDOException;

class Order
{
  public static function find(int $id): ?array
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("
      SELECT 
        o.id,
        o.subtotal,
        o.shipping,
        o.total,
        o.status,
        o.postal_code,
        o.address,
        o.cancel_token,
        i.product_id,
        i.variation,
        i.unit_price,
        i.quantity
      FROM orders o
      JOIN order_items i ON o.id = i.order_id WHERE o.id = ?
      ORDER BY o.created_at DESC");
    $stmt->execute([$id]);

    return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
  }

  public static function all(): array
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("
      SELECT 
        o.id,
        o.subtotal,
        o.shipping,
        o.total,
        o.status,
        o.postal_code,
        o.address,
        o.created_at
      FROM orders o
      ORDER BY o.created_at DESC
    ");
    $stmt->execute();

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as &$order) {
      $stmtItems = $db->prepare("
        SELECT 
          oi.product_id,
          p.name,
          p.price,
          p.variation,
          oi.quantity
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
      ");
      $stmtItems->execute([$order['id']]);
      $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }

    return $orders;
  }

  public static function create(float $subtotal, float $shipping, float $total, string $postal_code, string $address): int
  {
    $db = Database::getInstance();

    try {
      $db->beginTransaction();

      $cancelToken = bin2hex(random_bytes(32));

      $stmt = $db->prepare("INSERT INTO orders (subtotal, shipping, total, postal_code, address, cancel_token) VALUES (?, ?, ?, ?, ?, ?)");
      $ok1 = $stmt->execute([$subtotal, $shipping, $total, $postal_code, $address, $cancelToken]);

      $orderId = (int) $db->lastInsertId();
      $ok2 = true;

      foreach ($_SESSION["cart"] as $productId => $quantity) {
        $product = Product::find($productId);
        if (!$product) {
          throw new \Exception("Produto não encontrado.");
        }

        $availableStock = Stock::getQuantity($productId);
        if ($availableStock === null) {
          throw new \Exception("Produto sem controle de estoque.");
        }

        if ($availableStock < $quantity) {
          throw new \Exception("Estoque insuficiente para o produto: {$product['name']}");
        }

        $stmt2 = $db->prepare("INSERT INTO order_items (order_id, product_id, variation, unit_price, quantity) VALUES (?, ?, ?, ?, ?)");
        $ok2 = $stmt2->execute([
          $orderId,
          $productId,
          $product['variation'],
          $product['price'],
          $quantity
        ]);

        if (!Stock::decrement($productId, $quantity)) {
          throw new \Exception("Erro ao debitar estoque de {$product['name']}");
        }
      }

      if (!$ok1 || !$ok2) {
        throw new \Exception("Erro ao criar pedido.");
      }

      $db->commit();
      return $orderId;
    } catch (\Throwable $e) {
      if ($db->inTransaction()) {
        $db->rollBack();
      }
      throw $e;
    }
  }

  public static function updateStatus(int $id, string $status): bool
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
  }

  public static function update(int $id, string $status): bool
  {
    $db = Database::getInstance();

    try {
      $db->beginTransaction();

      $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
      $ok = $stmt->execute([$status, $id]);

      $db->commit();

      return $ok;
    } catch (PDOException $e) {
      if ($db->inTransaction()) {
        $db->rollBack();
      }
      throw $e;
    }
  }
}
