#!/bin/bash

echo "🚀 Iniciando build e setup do projeto..."
docker compose up --build -d

echo "⏳ Aguardando o banco de dados inicializar..."
sleep 5

echo "📦 Instalando dependências PHP..."
docker compose exec php composer install

echo "🔄 Gerando autoload otimizado..."
docker compose exec php composer dump-autoload -o

echo "📦 Executando migrations..."
docker compose exec php php /var/www/html/database/migrate.php

echo

echo "🧱 Rodando build do Tailwind com Node..."
docker compose up -d node

echo "✅ Setup finalizado!"

