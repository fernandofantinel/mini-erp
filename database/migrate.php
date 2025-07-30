<?php

require __DIR__ . '/../vendor/autoload.php';

use Core\Migration;

try {
  Migration::run();
  echo "✅ Migrations executadas com sucesso!";
} catch (Throwable $e) {
  echo "❌ Erro ao rodar as migrations: " . $e->getMessage();
}
