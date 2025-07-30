<?php

namespace App\Controllers;

use App\Models\Order;
use Core\Flash;

class WebhookController
{
  public function updateOrder(): void
  {
    header('Content-Type: application/json');
    $payload = file_get_contents("php://input");
    $data = json_decode($payload, true);

    if (!isset($data['id']) || !isset($data['status'])) {
      http_response_code(400);
      echo json_encode(['error' => 'ID do pedido e status são obrigatórios.']);
      return;
    }

    $orderId = (int) $data['id'];
    $status = trim($data['status']);

    $ok = Order::updateStatus($orderId, $status);

    if ($ok) {
      echo json_encode(['success' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Falha ao atualizar pedido.']);
    }
  }

  public function cancelOrder(): void
  {
    $orderId = $_GET['id'] ?? null;
    $token = $_GET['token'] ?? null;

    if (!$orderId || !is_numeric($orderId) || !$token) {
      http_response_code(400);
      Flash::push("error", "Pedido inválido ou token ausente.");
      header('Location: /');
      exit;
    }

    $order = Order::find($orderId);

    if (!$order || $order['cancel_token'] !== $token) {
      http_response_code(404);
      Flash::push("error", "Pedido não encontrado ou token inválido.");
      header('Location: /');
      exit;
    }

    $ok = Order::updateStatus((int)$orderId, 'cancelado');

    if ($ok) {
      $message = "Pedido " . $orderId . " cancelado com sucesso!";
    } else {
      $message = "Erro ao cancelar o pedido. Ele pode já ter sido cancelado ou não existir.";
    }

    Flash::push("message", $message);
    header('Location: /');
    exit;
  }
}
