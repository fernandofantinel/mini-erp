<?php

namespace Core;

class Migration
{
  public static function run(): void
  {
    $db = Database::getInstance();
    if (!$db) {
      throw new \Exception("❌ Conexão com banco de dados não foi executada com sucesso.");
    }

    $db->exec("
      CREATE TABLE IF NOT EXISTS migrations (
        id SERIAL PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )
    ");

    $files = glob(__DIR__ . '/../database/migrations/*.php');

    foreach ($files as $file) {
      $filename = basename($file);

      $stmt = $db->prepare("SELECT COUNT(*) FROM migrations WHERE filename = ?");
      $stmt->execute([$filename]);
      $alreadyExecuted = $stmt->fetchColumn() > 0;

      if ($alreadyExecuted) {
        echo "⚠️  Ignorada: $filename (já executada)\n";
        continue;
      }

      try {
        $sql = require $file;
        $db->exec($sql);

        $insert = $db->prepare("INSERT INTO migrations (filename) VALUES (?)");
        $insert->execute([$filename]);

        echo "✅ Executada: $filename\n";
      } catch (\Throwable $e) {
        echo "❌ Erro ao executar $filename: " . $e->getMessage() . "\n";
        exit(1);
      }
    }

    echo "✅ Todas migrações feitas.\n";
  }
}
