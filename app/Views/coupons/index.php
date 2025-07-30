<?php

use Core\Auth;
?>

<div>
  <h1 class="text-2xl mb-4">Cupons <?php if (Auth::isAdmin()): ?> (<a class="hover:underline" href="/coupons/create">Criar Cupom</a>)<?php endif; ?></h1>
</div>

<?php if (empty($coupons)): ?>
  <p class="text-white mt-8">Nenhum cupom cadastrado.</p>
<?php else: ?>

  <table class="table-auto w-full border border-white border-collapse text-white">
    <thead class="bg-blue-900 text-left">
      <tr>
        <th class="border border-white px-4 py-2">Nome</th>
        <th class="border border-white px-4 py-2">Desconto (%)</th>
        <th class="border border-white px-4 py-2">Valor mínimo (R$)</th>
        <th class="border border-white px-4 py-2">Data de expiração</th>
        <th class="border border-white px-4 py-2">Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($coupons as $coupon): ?>
        <tr class="hover:bg-blue-800">
          <td class="border border-white px-4 py-2"><?= htmlspecialchars($coupon['code']) ?></td>
          <td class="border border-white px-4 py-2"><?= number_format($coupon['discount'], 2, ',', '.') ?></td>
          <td class="border border-white px-4 py-2"><?= number_format($coupon['minimum_amount'], 2, ',', '.') ?></td>
          <?php
          $created_at = new DateTime($coupon['created_at'], new DateTimeZone('UTC'));
          $created_at->setTimezone(new DateTimeZone('America/Sao_Paulo'));
          ?>
          <td class="border border-white px-4 py-2"><?= htmlspecialchars($created_at->format('d-m-Y H:i:s')) ?></td>
          <?php if (Auth::isAdmin()): ?>
            <td class="border border-white px-4 py-2">
              <a href="/coupons/<?= $coupon['id'] ?>/edit" class="text-yellow-400 hover:underline">Editar</a>
              <a href="/coupons/<?= $coupon['id'] ?>/delete" onclick="return confirm('Você tem certeza?')" class="text-red-400 hover:underline ml-2">Excluir</a>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif; ?>