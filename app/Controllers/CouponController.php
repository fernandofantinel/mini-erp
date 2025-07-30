<?php

namespace App\Controllers;

use Core\View;
use Core\Validation;
use App\Models\Coupon;

class CouponController
{
  public function index(): void
  {
    $coupons = Coupon::all();
    View::render('coupons/index', ['coupons' => $coupons]);
  }

  public function create(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $code = $_POST['code'];
      $discount = $_POST['discount'];
      $minimum_amount = $_POST['minimum_amount'];
      $expires_at = date('Y-m-d', strtotime($_POST['expires_at']));

      $validation = Validation::validate([
        "code" => ["required"],
        "discount" => ["required"],
        "minimum_amount" => ["required"],
        "expires_at" => ["required"]
      ], $_POST);

      if ($validation->notPassed("coupon")) {
        header("Location: /coupons/create");
        exit;
      }

      Coupon::create($code, $discount, $minimum_amount, $expires_at);
      View::render('coupons/create');
      exit;
    }

    View::render('coupons/create');
  }

  public function edit(int $id): void
  {
    $coupon = Coupon::find($id);

    if (!$coupon) {
      http_response_code(404);
      echo "Cupom não encontrado.";
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $code = $_POST['code'];
      $discount = $_POST['discount'];
      $minimum_amount = $_POST['minimum_amount'];
      $expires_at = date('Y-m-d', strtotime($_POST['expires_at']));

      $validation = Validation::validate([
        "code" => ["required"],
        "discount" => ["required"],
        "minimum_amount" => ["required"],
        "expires_at" => ["required"]
      ], $_POST);

      if ($validation->notPassed("coupon")) {
        header("Location: /coupons/$id/edit");
        exit;
      }

      Coupon::update($id, $code, $discount, $minimum_amount, $expires_at);
      header('Location: /coupons');
      exit;
    }

    View::render('coupons/edit', [
      'coupon' => $coupon
    ]);
  }

  public function delete(int $id): void
  {
    Coupon::delete($id);
    header('Location: /coupons');
    exit;
  }

  public function apply(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      header('Content-Type: application/json');

      $code = $_GET['code'] ?? '';
      $subtotal = (float) ($_GET['subtotal'] ?? 0);

      $discount = Coupon::applyDiscount($code, $subtotal);

      if ($discount > 0) {
        echo json_encode([
          'success' => true,
          'discount' => $discount
        ]);
      } else {
        http_response_code(400);
        echo json_encode([
          'success' => false,
          'message' => 'Cupom inválido ou subtotal insuficiente.'
        ]);
      }

      exit;
    }

    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode([
      'success' => false,
      'message' => 'Método não permitido.'
    ]);
    exit;
  }
}
