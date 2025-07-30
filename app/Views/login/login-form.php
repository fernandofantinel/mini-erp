<?php

use Core\Flash;
?>

<div class="flex justify-center">
  <div class="grid grid-cols-2 items-start gap-12 p-8">

    <div class="border border-white rounded p-4">
      <h1 class="text-2xl mb-4">Login</h1>
      <form action="/login" method="POST">
        <?php if ($validations = Flash::get("validations_login")) : ?>
          <div class="w-80 border-2 border-red-800 bg-red-900 text-red-400 px-4 py-1 rounded-md text-sm font-bold mb-8">
            <ul>
              <?php foreach ($validations as $validation) : ?>
                <li><?= $validation ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        <label>
          Email:<br>
          <input class="border border-white rounded px-2 py-1 w-80" type="email" name="email">
        </label>
        <br><br>
        <label>
          Senha<br>
          <input class="border border-white rounded px-2 py-1 w-80" type="password" name="password">
        </label>
        <br><br>
        <button class="bg-white text-blue-600 font-bold px-4 py-2 rounded cursor-pointer w-full" type="submit">Logar</button>
      </form>
    </div>

    <div class="border border-white rounded p-4">
      <h1 class="text-2xl mb-4">Cadastro</h1>
      <form action="/register" method="POST">
        <?php if ($validations = Flash::get("validations_register")) : ?>
          <div class="w-80 border-2 border-red-800 bg-red-900 text-red-400 px-4 py-1 rounded-md text-sm font-bold mb-8">
            <ul>
              <?php foreach ($validations as $validation) : ?>
                <li><?= $validation ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        <label>
          Nome:<br>
          <input class="border border-white rounded px-2 py-1 w-80" type="text" name="name">
        </label>
        <br><br>
        <label>
          E-mail:<br>
          <input class="border border-white rounded px-2 py-1 w-80" type="email" name="email">
        </label>
        <br><br>
        <label>
          Senha<br>
          <input class="border border-white rounded px-2 py-1 w-80" type="password" name="password">
        </label>
        <br><br>
        <button class="bg-white text-blue-600 font-bold px-4 py-2 rounded cursor-pointer w-full" type="submit">Cadastrar</button>
      </form>
    </div>
  </div>
</div>