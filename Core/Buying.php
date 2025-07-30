<?php

namespace Core;

use App\Models\Order;
use App\Controllers\OrderController;
use Core\View;
use Core\Email;

class Buying
{
  public function address(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $validation = Validation::validate([
        "zip_code" => ["required"],
        "state" => ["required"],
        "city" => ["required"],
        "neighborhood" => ["required"],
        "street" => ["required"],
        "number" => ["required"]
      ], $_POST);

      if ($validation->notPassed("address")) {
        header("location: /buy/address");
        exit;
      }

      $_SESSION['address'] = [
        "zip_code" => $_POST['zip_code'] ?? '',
        "state" => $_POST['state'] ?? '',
        "city" => $_POST['city'] ?? '',
        "neighborhood" => $_POST['neighborhood'] ?? '',
        "street" => $_POST['street'] ?? '',
        "number" => $_POST['number'] ?? '',
        "complement" => $_POST['complement'] ?? ''
      ];

      View::render('buying/shipping-coupons');
      exit;
    }

    View::render('buying/address');
  }

  public function finish(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $subtotal = (float) ($_POST['subtotal'] ?? 0);
      $shipping = (float) ($_POST['shipping'] ?? 0);
      $total = (float) ($_POST['total'] ?? 0);

      $orderId = (new OrderController)->create(
        $subtotal,
        $shipping,
        $total
      );

      $order = Order::find($orderId);

      $emailBody = $this->createBodyContent($orderId, $order['cancel_token']);

      $this->sendEmail($emailBody);

      $this->updateStatus($orderId, 'pago');

      View::render('buying/finish', [
        'orderId' => $orderId,
      ]);
      exit;
    }
  }

  public function createBodyContent(int $orderId, string $cancelToken): string
  {
    extract(['orderId' => $orderId, 'token' => $cancelToken]);

    $body = require dirname(__DIR__, 1) . "/app/Views/emails/order_confirmation.php";

    return $body;
  }

  public function sendEmail($body): void
  {
    $email = $_SESSION['auth']['email'];
    $subject = 'Confirmação de pedido';

    Email::send($email, $subject, $body);

    $_SESSION['address'] = [];
    $_SESSION['cart'] = [];
  }

  public function updateStatus(int $orderId, string $status): void
  {
    $payload = json_encode([
      'id' => $orderId,
      'status' => $status
    ]);

    $options = [
      'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => $payload,
      ]
    ];

    $context = stream_context_create($options);
    file_get_contents('http://nginx/update-order', false, $context);
  }
}
