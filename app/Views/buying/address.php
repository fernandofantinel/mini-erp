<?php

use Core\Flash;
?>

<div class="flex flex-col items-center gap-4">
  <div class="text-2xl">Endereço</div>
  <form action="/buy/address" method="POST">
    <?php if ($validations = Flash::get("validations_address")) : ?>
      <div class="w-80 border-2 border-red-800 bg-red-900 text-red-400 px-4 py-1 rounded-md text-sm font-bold mb-8">
        <ul>
          <?php foreach ($validations as $validation) : ?>
            <li><?= $validation ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label>
          CEP (apenas números):<br>
          <input class="border border-white rounded px-2 outline-hidden" type="text" name="zip_code" id="zip_code" maxlength="8" required>
        </label>
      </div>
      <div>
        <label>
          Estado:<br>
          <input class="border border-white rounded px-2 outline-hidden" type="text" name="state" id="state" required>
        </label>
      </div>
      <div>
        <label>
          Cidade:<br>
          <input class="border border-white rounded px-2 outline-hidden" type="text" name="city" id="city" required>
        </label>
      </div>
      <div>
        <label>
          Bairro:<br>
          <input class="border border-white rounded px-2 outline-hidden" type="text" name="neighborhood" id="neighborhood" required>
        </label>
      </div>
      <div class="">
        <label>
          Logradouro:<br>
          <input class="border border-white rounded px-2 outline-hidden" type="text" name="street" id="street" required>
        </label>
      </div>
      <div>
        <label>
          Número:<br>
          <input class="border border-white rounded px-2 outline-hidden" type="text" name="number" id="number" required>
        </label>
      </div>
      <div>
        Complemento:<br>
        <input class="border border-white rounded px-2 outline-hidden" type="text" name="complement" id="complement">
      </div>
    </div>

    <div class="flex gap-4 w-40 mt-8">
      <button class="flex-1 h-12 flex items-center justify-center bg-white text-blue-600 font-bold border border-white px-4 py-2 rounded cursor-pointer" type="submit">Prosseguir</button>

      <a class="flex-1 h-12 flex items-center justify-center bg-white text-blue-600 font-bold border border-white px-4 py-2 rounded cursor-pointer" href="/cart">Cancelar</a>
    </div>
  </form>
</div>