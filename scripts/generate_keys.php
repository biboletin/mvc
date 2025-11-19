#!/usr/bin/env php
<?php

// Ensure the script is run from CLI
use Random\RandomException;

if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the terminal.\n";
    exit(1);
}

$envFile = __DIR__ . '/../.env';

// Generate a secure salt and hashes
try {
    $keys = [
        'APP_KEY' => 'base64:' . base64_encode(random_bytes(32)),
        'SECURITY_KEY' => 'base64:' . base64_encode(random_bytes(32)),
        'SECURITY_SALT' => 'base64:' . base64_encode(random_bytes(32)),
        'ENCRYPTION_KEY' => 'base64:' . base64_encode(random_bytes(32)),
        'ENCRYPTION_SALT' => 'base64:' . base64_encode(random_bytes(32)),
        'HASH_SALT' => 'base64:' . base64_encode(random_bytes(32)),
        'TFA_SECRET_KEY' => 'base64:' . base64_encode(random_bytes(32)),
    ];
} catch (RandomException $e) {
    die($e->getMessage());
}


// Read existing .env or start fresh
$envContent = '';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);

    foreach ($keys as $key => $value) {
        if (preg_match('/^' . $key . '=.*$/m', $envContent)) {
            $envContent = preg_replace('/^' . $key . '=.*$/m', $key . '=' . $value, $envContent);
        } else {
            $envContent .= $key . '=' . $value;
        }
    }
}

// Write back to .env
if (file_put_contents($envFile, $envContent) !== false) {
    echo "✅ New keys generated and stored in .env\n";
    echo "Keys:\r\n" . implode("\r\n", array_keys($keys)) . "\n";
} else {
    echo "❌ Failed to write to .env file.\n";
    exit(1);
}
