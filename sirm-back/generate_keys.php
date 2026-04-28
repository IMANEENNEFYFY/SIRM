<?php
$config = [
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'config' => 'C:/php/extras/ssl/openssl.cnf',
];

if (!is_dir('config/jwt')) {
    mkdir('config/jwt', 0755, true);
}

$privateKey = openssl_pkey_new($config);

if (!$privateKey) {
    echo "Erreur : " . openssl_error_string() . "\n";
    exit(1);
}

openssl_pkey_export($privateKey, $privateKeyPem, null, $config);
file_put_contents('config/jwt/private.pem', $privateKeyPem);

$publicKey = openssl_pkey_get_details($privateKey);
file_put_contents('config/jwt/public.pem', $publicKey['key']);

echo "Cles generees avec succes!\n";