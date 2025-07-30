<?php

use Core\Auth;
?>

<div>
  <h1 class="text-2xl mb-4">Produtos <?php if (Auth::isAdmin()): ?> (<a class="hover:underline" href="/products/create">Criar Produto</a>)<?php endif; ?></h1>
</div>

<?php if (empty($products)): ?>
  <p class="text-white mt-8">Nenhum produto cadastrado.</p>
<?php else: ?>
  <table class="table-auto w-full border border-white border-collapse text-white">
    <thead class="bg-blue-900 text-left">
      <tr>
        <th class="border border-white px-4 py-2">Nome</th>
        <th class="border border-white px-4 py-2">Preço (R$)</th>
        <th class="border border-white px-4 py-2">Variação</th>
        <th class="border border-white px-4 py-2">Estoque</th>
        <th class="border border-white px-4 py-2">Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product): ?>
        <tr class="hover:bg-blue-800">
          <td class="border border-white px-4 py-2"><?= htmlspecialchars($product['name']) ?></td>
          <td class="border border-white px-4 py-2"><?= number_format($product['price'], 2, ',', '.') ?></td>
          <td class="border border-white px-4 py-2"><?= htmlspecialchars($product['variation']) ?></td>
          <td class="border border-white px-4 py-2"><?= htmlspecialchars($product['quantity']) ?></td>
          <?php if (Auth::isAdmin()): ?>
            <td class="border border-white px-4 py-2">
              <a href="/products/<?= $product['id'] ?>/edit" class="text-yellow-400 hover:underline">Editar</a>
              <a href="/products/<?= $product['id'] ?>/delete" onclick="return confirm('Você tem certeza?')" class="text-red-400 hover:underline ml-2">Excluir</a>
            </td>
          <?php else: ?>
            <td class="border border-white px-4 py-2">
              <form action="/cart/<?= $product['id'] ?>/add" method="POST">
                <button type="submit" class="bg-white text-blue-600 font-bold px-2 py-1 rounded cursor-pointer">Adicionar</button>
              </form>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif; ?>

<?php if (!Auth::isAdmin()): ?>
  <div class="flex justify-end mt-4">
    <a href="/cart" class="bg-white text-blue-600 font-bold px-4 py-2 rounded cursor-pointer" type="submit">Ir para o carrinho</a>
  </div>
<?php endif; ?>