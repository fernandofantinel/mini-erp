<?php

use Core\Auth;
use Core\Flash;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/styles.css">
  <script src="/js/scripts.js" defer></script>
  <title>Mini ERP</title>
</head>

<body class="bg-gray-100 text-gray-800">

  <nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
    <div class="text-xl font-bold">
      <a href="/" class="text-blue-600 hover:underline">Mini ERP</a>
    </div>
    <div class="flex space-x-4 font-bold">
      <div class="flex space-x-4 font-bold">
        <?php if (!Auth::check()): ?>
          <a href="/login" class="hover:underline">Login</a>
        <?php else: ?>
          <a href="/products" class="hover:underline">Produtos</a>

          <?php if (Auth::isAdmin()): ?>
            <a href="/coupons" class="hover:underline">Cupons</a>
            <a href="/orders" class="hover:underline">Pedidos</a>
          <?php elseif (Auth::isUser()): ?>
            <a href="/cart" class="hover:underline">Carrinho</a>
          <?php endif; ?>

          <a href="/logout" onclick="return confirm('Você tem certeza?')" class="text-blue-600 hover:underline cursor-pointer">Logout</a>
        <?php endif; ?>

        <?php if (Auth::check()): ?>
          <div class="text-sm text-gray-500 border border-gray-500 px-2 py-1 rounded">
            <?= htmlspecialchars(Auth::user()['email']) ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <main class="min-h-screen p-6 bg-blue-600 text-white">
    <?php if ($message = Flash::get("message")): ?>
      <div class="border-2 border-green-800 bg-green-900 text-green-400 px-4 py-1 rounded-md text-sm font-bold">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <?php if ($message = Flash::get("error")): ?>
      <div class="border-2 border-red-800 bg-red-900 text-red-400 px-4 py-1 rounded-md text-sm font-bold">
        <?= $message ?>
      </div>
    <?php endif; ?>