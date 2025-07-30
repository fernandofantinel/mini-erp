<div>
  <h1 class="text-2xl mb-4">Pedidos</h1>
</div>

<?php if (empty($orders)): ?>
  <p class="text-white mt-8">Nenhum pedido realizado.</p>
<?php else: ?>

  <div class="flex flex-col gap-4">
    <?php foreach ($orders as $order): ?>
      <div class="border border-white p-4 mb-4 rounded bg-blue-800 <?php if ($order['status'] == 'cancelado') echo 'opacity-60' ?>">
        <div class="grid grid-cols-3 gap-2">
          <div>
            <label class="font-bold">ID:</label>
            <span><?= htmlspecialchars($order['id']) ?></span>
          </div>
          <div>
            <label class="font-bold">Subtotal (R$):</label>
            <span><?= number_format($order['subtotal'], 2, ',', '.') ?></span>
          </div>
          <div>
            <label class="font-bold">Frete (R$):</label>
            <span><?= number_format($order['shipping'], 2, ',', '.') ?></span>
          </div>
          <div>
            <label class="font-bold">Total (R$):</label>
            <span><?= number_format($order['total'], 2, ',', '.') ?></span>
          </div>
          <div>
            <label class="font-bold">CEP:</label>
            <span><?= htmlspecialchars($order['postal_code']) ?></span>
          </div>
          <div>
            <label class="font-bold">Endereço:</label>
            <span><?= htmlspecialchars($order['address']) ?></span>
          </div>
          <div>
            <label class="font-bold">Status:</label>
            <?php switch ($order['status']) {
              case 'pago':
                $statusClass = 'inline bg-green-500 text-white p-1 rounded';
                break;
              case 'cancelado':
                $statusClass = 'inline bg-red-500 text-white p-1 rounded';
                break;
              default:
                $statusClass = 'inline bg-yellow-500 text-white p-1 rounded';
            } ?>
            <div class="<?= $statusClass ?>">
              <span><?= htmlspecialchars(strtoupper($order['status'])) ?></span>
            </div>
          </div>
          <div>
            <label class="font-bold">Data do pedido:</label>
            <?php
            $created_at = new DateTime($order['created_at'], new DateTimeZone('UTC'));
            $created_at->setTimezone(new DateTimeZone('America/Sao_Paulo'));
            ?>
            <span><?= htmlspecialchars($created_at->format('d-m-Y H:i:s')) ?></span>
          </div>
        </div>
        <div class="mt-6">
          <table class="table-auto w-full border border-white border-collapse text-white">
            <thead class="bg-blue-900 text-left">
              <tr>
                <th class="border border-white px-4 py-2">Produto</th>
                <th class="border border-white px-4 py-2">Variação</th>
                <th class="border border-white px-4 py-2">Preço</th>
                <th class="border border-white px-4 py-2">Quantidade</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($order["items"] as $item): ?>
                <tr class="hover:bg-blue-800">
                  <td class="border border-white px-4 py-2"><?= htmlspecialchars($item['product_id']) ?></td>
                  <td class="border border-white px-4 py-2"><?= htmlspecialchars($item['variation']) ?></td>
                  <td class="border border-white px-4 py-2"><?= number_format($item['price'], 2, ',', '.') ?></td>
                  <td class="border border-white px-4 py-2"><?= htmlspecialchars($item['quantity']) ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endforeach ?>
  </div>
<?php endif; ?>