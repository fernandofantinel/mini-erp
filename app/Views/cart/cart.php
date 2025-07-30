<?php

use Core\Cart;

$cart = new Cart();
$products = $cart->getCartItems();
?>

<h1 class="text-2xl mb-4">Carrinho<?php if (!empty($products)): ?> (<a href="/cart/clear">Esvaziar carrinho</a>) <?php endif; ?></h1>

<?php if (empty($products)): ?>
  <p class="text-white mt-8">Seu carrinho está vazio.</p>
<?php else: ?>
  <table class="w-full table-auto border border-white border-collapse text-white">
    <thead class="bg-blue-900 text-left">
      <tr>
        <th class="border border-white px-4 py-2">Nome</th>
        <th class="border border-white px-4 py-2">Quantidade</th>
        <th class="border border-white px-4 py-2">Preço (R$)</th>
        <th class="border border-white px-4 py-2">Total (R$)</th>
        <th class="border border-white px-4 py-2">Ações</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-stone-600">
      <?php foreach ($products as $product): ?>
        <tr class="hover:bg-blue-800">
          <td class="border border-white px-4 py-2"><?= htmlspecialchars($product["name"]) ?></td>
          <td class="border border-white px-4 py-2"><?= htmlspecialchars($product["quantity"]) ?></td>
          <td class="border border-white px-4 py-2">R$ <?= number_format($product['price'], 2, ',', '.') ?></td>
          <td class="border border-white px-4 py-2">R$ <?= number_format($product['total'], 2, ',', '.') ?></td>
          <td class="border border-white px-4 py-2">
            <a href="/cart/<?= $product['id'] ?>/remove"
              onclick="return confirm('Você tem certeza?')"
              class="text-yellow-400 hover:underline">
              Excluir
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class="mt-8 flex justify-end">
    <a href="/buy/address"
      class="bg-white text-blue-600 font-bold px-4 py-2 rounded">
      Prosseguir para compra
    </a>
  </div>
<?php endif; ?>