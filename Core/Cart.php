<?php

namespace Core;

use App\Models\Product;
use Core\View;

class Cart
{
  private array $items = [];

  public function __construct()
  {
    $this->items = $_SESSION['cart'] ?? [];
  }

  public function getCartItems(): array
  {
    $cartItems = [];

    foreach ($this->items as $productId => $quantity) {
      $product = Product::find($productId);

      if ($product) {
        $cartItems[] = [
          'id' => $product['id'],
          'name' => $product['name'],
          'price' => $product['price'],
          'quantity' => $quantity,
          'total' => $product['price'] * $quantity
        ];
      }
    }

    return $cartItems;
  }

  public function list(): void
  {
    View::render('cart/cart');
  }

  public function add(int $productId, int $quantity = 1): void
  {
    $product = Product::find($productId);

    if (!$product) {
      Flash::push("error", "Produto não encontrado.");
      header("Location: /products");
      exit;
    }

    $stock = (int) $product['quantity'];
    $current = $this->items[$productId] ?? 0;
    $newTotal = $current + $quantity;

    if ($newTotal > $stock) {
      $disponivel = $stock - $current;

      if ($disponivel <= 0) {
        Flash::push("error", "Estoque esgotado para esse produto.");
      }

      header("Location: /products");
      exit;
    }

    $this->items[$productId] = $newTotal;
    $_SESSION['cart'] = $this->items;

    Flash::push("message", "Produto adicionado ao carrinho.");
    header("Location: /products");
    exit;
  }

  public function remove(int $productId): void
  {
    unset($this->items[$productId]);
    $_SESSION['cart'] = $this->items;
    header("Location: /cart");
    exit;
  }

  public function clear(): void
  {
    $this->items = [];
    $_SESSION['cart'] = [];
    header("Location: /cart");
    exit;
  }

  public function subtotal(): float
  {
    $total = 0.0;
    foreach ($this->items as $productId => $quantity) {
      $product = Product::find($productId);
      if (!$product) continue;

      $price = $product['price'] ?? 0;

      if (!is_numeric($price) || $price < 0) $price = 0;
      if (!is_int($quantity) || $quantity < 0) $quantity = 0;

      $total += $price * $quantity;
    }

    return $total;
  }

  public function shipping(float $subtotal): float
  {
    if ($subtotal >= 200.00) return 0.0;
    if ($subtotal >= 52.00 && $subtotal <= 166.59) return 15.00;

    return 20.00;
  }
}
