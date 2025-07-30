<?php

use Core\Flash;
?>

<div class="flex flex-col justify-between items-center mb-4">
  <h1 class="text-2xl mb-4">Editar Cupom</h1>

  <?php if ($validations = Flash::get("validations_coupon")) : ?>
    <div class="w-80 border-2 border-red-800 bg-red-900 text-red-400 px-4 py-1 rounded-md text-sm font-bold mb-8">
      <ul>
        <?php foreach ($validations as $validation) : ?>
          <li><?= $validation ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="/coupons/<?= $coupon['id'] ?>/edit" method="POST">
    <label>
      Nome:<br>
      <input class="border border-white rounded px-2 outline-hidden" type="text" name="code" placeholder="CUPOM10, CUPOM20..." value="<?= htmlspecialchars($coupon['code']) ?>" required>
    </label>
    <br><br>
    <label>
      Desconto (%):<br>
      <input class="border border-white rounded px-2 outline-hidden" type="number" name="discount" step="1" min="1" value="<?= htmlspecialchars($coupon['discount']) ?>" required>
    </label>
    <br><br>
    <label>
      Valor mínimo (R$):<br>
      <input class="border border-white rounded px-2 outline-hidden" type="text" name="minimum_amount" value="<?= htmlspecialchars($coupon['minimum_amount']) ?>" required>
    </label>
    <br><br>
    <label>
      Data de expiração:<br>
      <input class="border border-white rounded px-2 outline-hidden" type="date" name="expires_at" value="<?= htmlspecialchars($coupon['expires_at']) ?>" required>
    </label>
    <br><br>
    <div class="flex gap-4 w-40">
      <button class="flex-1 h-12 flex items-center justify-center bg-white text-blue-600 font-bold border border-white px-4 py-2 rounded cursor-pointer" type="submit">Salvar</button>
      <a class="flex-1 h-12 flex items-center justify-center bg-white text-blue-600 font-bold border border-white px-4 py-2 rounded cursor-pointer" href="/coupons">Cancelar</a>
    </div>
  </form>
</div>