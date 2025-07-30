<?php

use Core\Cart;

$cart = new Cart;
$subtotal = $cart->subtotal();
$shipping = $cart->shipping($subtotal);
$total = $subtotal + $shipping;
?>

<div class="flex flex-col items-center gap-4">
  <div class="text-2xl">Pagamento</div>
  <div class="text-lg">Subtotal (R$): <?= number_format($subtotal, 2, ',', '.') ?></div>
  <div class="text-lg">Frete (R$): <?= number_format($shipping, 2, ',', '.') ?></div>

  <table class="table-auto border border-white border-collapse text-white">
    <thead>
      <tr class="bg-blue-900 text-white">
        <th class="border border-white px-4 py-2">Subtotal (R$)</th>
        <th class="border border-white px-4 py-2">Frete (R$)</th>
      </tr>
    </thead>
    <tbody>
      <tr class="hover:bg-blue-800 text-center">
        <td class="border border-white px-4 py-2">R$ 0,00 — R$ 51,99</td>
        <td class="border border-white px-4 py-2">20,00</td>
      </tr>
      <tr class="hover:bg-blue-800 text-center">
        <td class="border border-white px-4 py-2">R$ 52,00 — R$ 166,59</td>
        <td class="border border-white px-4 py-2">15,00</td>
      </tr>
      <tr class="hover:bg-blue-800 text-center">
        <td class="border border-white px-4 py-2">R$ 200,00 ou mais</td>
        <td class="border border-white px-4 py-2">Grátis</td>
      </tr>
    </tbody>
  </table>
  <form class="flex flex-col items-center gap-4" action="/buy/finish" method="POST">
    <label class="mt-4">
      Cupom:
      <input class="border border-white rounded px-2 outline-hidden" type="text" id="coupon" name="coupon" placeholder="CUPOM10, CUPOM20...">
    </label>
    <input type="hidden" name="subtotal" value="<?= $subtotal ?>" id="subtotal">
    <input type="hidden" name="shipping" value="<?= $shipping ?>" id="shipping">
    <input type="hidden" name="total" value="<?= $total ?>" id="total">
    <button type="button" class="flex-1 h-12 flex items-center justify-center bg-white text-blue-600 font-bold border border-white px-4 py-2 rounded cursor-pointer" id="apply_coupon">Aplicar</button>
    <div class="flex items-center gap-1">
      <label>Desconto (R$): </label>
      <span class="text-lg" id="discount">0</span>
    </div>
    <div class="flex items-center gap-1">
      <label>Total (R$): </label>
      <span class="text-lg" id="show_total"><?= number_format($subtotal + $shipping, 2, ',', '.') ?></span>
    </div>
    <button class="flex-1 h-12 flex items-center justify-center bg-white text-blue-600 font-bold border border-white px-4 py-2 rounded cursor-pointer" type="submit">Finalizar</button>
  </form>
</div>