<?php

return <<<SQL
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subtotal DECIMAL(10,2),
    shipping DECIMAL(10,2),
    total DECIMAL(10,2),
    status ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente',
    postal_code VARCHAR(9),
    address TEXT,
    cancel_token VARCHAR(64) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
SQL;
