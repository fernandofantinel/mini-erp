<?php

$password = '123';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

return <<<SQL
INSERT INTO users (name, email, password)
VALUES ('admin', 'admin@email.com', '{$hashedPassword}'
);
SQL;
