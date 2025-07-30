<?php

use Core\Flash;
?>

<div class="flex flex-col justify-between items-center mb-4">
  <h1 class="text-2xl mb-4">Criar Produto</h1>

  <?php if ($validations = Flash::get("validations_product")) : ?>
    <div class="w-80 border-2 border-red-800 bg-red-900 text-red-400 px-4 py-1 rounded-md text-sm font-bold mb-8">
      <ul>
        <?php foreach ($validations as $validation) : ?>
          <li><?= $validation ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="/products/create" method="POST">
    <label>
      Nome:<br>
      <input class="border border-white rounded px-2 outline-hidden" type="text" name="name" required>
    </label>
    <br><br>
    <label>
      Preço (R$):<br>
      <input class="border border-white rounded px-2 outline-hidden" type="number" name="price" step="0.01" min="0" required>
    </label>
    <br><br>
    <label>
      Variação:<br>
      <input class="border border-white rounded px-2 outline-hidden" type="text" name="variation" required>
    </label>
    <br><br>
    <label>
      Quantidade:<br>
      <input class="border border-white rounded px-2 outline-hidden" type="number" name="quantity" step="1" min="0" required>
    </label>
    <br><br>
    <div class="flex gap-4 w-40">
      <button class="flex-1 h-12 flex items-center justify-center bg-white text-blue-600 font-bold border border-white px-4 py-2 rounded cursor-pointer" type="submit">Salvar</button>
      <a class="flex-1 h-12 flex items-center justify-center bg-white text-blue-600 font-bold border border-white px-4 py-2 rounded cursor-pointer" href="/products">Cancelar</a>
    </div>
  </form>
</div>