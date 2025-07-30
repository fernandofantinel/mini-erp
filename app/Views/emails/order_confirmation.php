<?php

use App\Models\Product;

$cancelUrl = 'http://localhost:8888/cancel-order?id=' . $orderId . '&token=' . $cancelToken;

$productRows = '';

foreach ($_SESSION["cart"] as $productId => $quantity) {
  $product = Product::find($productId);
  if (!$product) continue;

  $productRows .= '
    <tr>
      <td style="padding: 8px; border-bottom: 1px solid #ddd;">' . $product['name'] . '</td>
      <td style="text-align: center; padding: 8px; border-bottom: 1px solid #ddd;">' . $product['variation'] . '</td>
      <td style="text-align: center; padding: 8px; border-bottom: 1px solid #ddd;">' . $quantity . '</td>
      <td style="text-align: right; padding: 8px; border-bottom: 1px solid #ddd;">' . number_format($product['price'], 2, ',', '.') . '</td>
    </tr>
  ';
}

$body = '
  <div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; border-radius: 8px;">
    <h2 style="color: #333; text-align: center;">Confirmação de Pedido</h2>
    <p style="color: #555; font-size: 16px;">
      Olá, obrigado por comprar conosco!
    </p>

    <p style="color: #555; font-size: 16px;">
      Seu pedido foi recebido e está sendo processado. Abaixo estão os detalhes do seu pedido:
    </p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <thead>
        <tr>
          <th style="text-align: left; padding: 8px; background-color: #eee;">Produto</th>
          <th style="text-align: center; padding: 8px; background-color: #eee;">Variação</th>
          <th style="text-align: center; padding: 8px; background-color: #eee;">Quantidade</th>
          <th style="text-align: right; padding: 8px; background-color: #eee;">Preço</th>
        </tr>
      </thead>
      <tbody>
        ' . $productRows . '
      </tbody>
    </table>

    <div style="text-align: center; margin-top: 30px;">
      <a href="' . $cancelUrl . '" style="display: inline-block; padding: 12px 24px; background-color: #d9534f; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
        Cancelar Pedido
      </a>
    </div>

    <p style="color: #999; font-size: 12px; text-align: center; margin-top: 40px;">
      Mini ERP - Todos os direitos reservados
    </p>
  </div>
';

return $body;
