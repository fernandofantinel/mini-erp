<?php

namespace App\Controllers;

use App\Models\Order;
use Core\View;

class OrderController
{
  public function index(): void
  {
    $orders = Order::all();
    View::render('orders/index', ['orders' => $orders]);
  }

  public function create(float $subtotal, float $shipping, float $total): int
  {
    $postal_code = $_SESSION["address"]["zip_code"] ?? '';

    $state = $_SESSION["address"]["state"] ?? '';
    $city = $_SESSION["address"]["city"] ?? '';
    $neighborhood = $_SESSION["address"]["neighborhood"] ?? '';
    $street = $_SESSION["address"]["street"] ?? '';
    $number = $_SESSION["address"]["number"] ?? '';
    $complement = $_SESSION["address"]["complement"] ?? '';

    $address = $state . ' - ' .
      $city . ' - ' .
      $neighborhood . ' - ' .
      $street . ', ' .
      $number . ' - ' .
      $complement;

    $orderId = Order::create($subtotal, $shipping, $total, $postal_code, $address);

    return $orderId;
  }
}
