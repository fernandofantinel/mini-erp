<?php

return [
    "database" => [
        'DB_HOST' => getenv('DB_HOST') ?: 'mysql',
        'DB_NAME' => getenv('MYSQL_DATABASE') ?: 'minierp',
        'DB_USER' => getenv('MYSQL_USER') ?: 'user',
        'DB_PASS' => getenv('MYSQL_PASSWORD') ?: 'userpass'
    ],
    "mail" => [
        'MAIL_Host' => getenv('MAIL_Host'),
        'MAIL_SMTPAuth' => getenv('MAIL_SMTPAuth'),
        'MAIL_Username' => getenv('MAIL_Username'),
        'MAIL_Password' => getenv('MAIL_Password'),
        'MAIL_SMTPSecure' => getenv('MAIL_SMTPSecure'),
        'MAIL_Port' => getenv('MAIL_Port')
    ]
];
