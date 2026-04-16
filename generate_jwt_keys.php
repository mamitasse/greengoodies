<?php

$config = [
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];

// Générer la clé privée
$res = openssl_pkey_new($config);

// Exporter la clé privée
openssl_pkey_export($res, $privateKey);

// Récupérer la clé publique
$publicKey = openssl_pkey_get_details($res);
$publicKey = $publicKey["key"];

// Créer le dossier si besoin
if (!is_dir(__DIR__ . '/config/jwt')) {
    mkdir(__DIR__ . '/config/jwt', 0777, true);
}

// Sauvegarder les fichiers
file_put_contents(__DIR__ . '/config/jwt/private.pem', $privateKey);
file_put_contents(__DIR__ . '/config/jwt/public.pem', $publicKey);

echo "Clés JWT générées avec succès !";