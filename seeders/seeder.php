<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Charger les variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Récupération des variables
$dbDriver = $_ENV['DB_DRIVER'] ?? 'pgsql';
$dbHost   = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbPort   = $_ENV['DB_PORT'] ?? '5432';
$dbName   = $_ENV['DB_NAME'] ?? 'test';
$dbUser   = $_ENV['DB_USERNAME'] ?? 'postgres';
$dbPass   = $_ENV['DB_PASSWORD'] ?? '';

// Construction du DSN selon le driver
$dsn = "$dbDriver:host=$dbHost;dbname=$dbName;port=$dbPort";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie à la base de données\n";
} catch (PDOException $e) {
    die("❌ Connexion échouée : " . $e->getMessage() . "\n");
}

try {
    $pdo->beginTransaction();

    // Truncate adapté selon le SGBD
    if ($dbDriver === 'mysql') {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $pdo->exec("TRUNCATE TABLE transaction");
        $pdo->exec("TRUNCATE TABLE compte");
        $pdo->exec("TRUNCATE TABLE users");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    } else {
        $pdo->exec("TRUNCATE TABLE transaction RESTART IDENTITY CASCADE");
        $pdo->exec("TRUNCATE TABLE compte RESTART IDENTITY CASCADE");
        $pdo->exec("TRUNCATE TABLE users RESTART IDENTITY CASCADE");
    }

    echo "✅ Tables vidées avec succès\n";

    // 1. Utilisateurs sans password_hash
    $users = [
        [
            'Niang',
            'Madié',
            'die_' . uniqid(),
            'passer123',
            '21234567890_' . uniqid(),
            null, null, null, 'client'
        ],
        [
            'Niang',
            'aidasa',
            'aida_' . uniqid(),
            'passer',
            '21388888880_' . uniqid(),
            null, null, null, 'client'
        ],
    ];

    $stmtUser = $pdo->prepare("
        INSERT INTO users (nom, prenom, login, password, numerocarteidentite, photorecto, photoverso, adresse, typeuser) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $userIds = [];
    foreach ($users as $user) {
        $stmtUser->execute($user);
        $userIds[] = $pdo->lastInsertId();
    }
    echo "✅ Utilisateurs insérés\n";

    // 2. Comptes
    $comptes = [
        ['CPT-001', date('Y-m-d H:i:s'), 465000.00, '778801947', 'principal', $userIds[0]],
        ['CPT-002', date('Y-m-d H:i:s'), 30000.00, '775159909', 'secondaire', $userIds[0]],
        ['CPT-003', date('Y-m-d H:i:s'), 5000.00, '773452800', 'secondaire', $userIds[0]],
    ];

    $stmtCompte = $pdo->prepare("
        INSERT INTO compte (numero, datecreation, solde, numerotel, typecompte, userid) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $compteIds = [];
    foreach ($comptes as $compte) {
        $stmtCompte->execute($compte);
        $compteIds[] = $pdo->lastInsertId();
    }
    echo "✅ Comptes insérés\n";

    // 3. Transactions
    $transactions = [
        [date('Y-m-d H:i:s'), 'depot', 10000.00, $compteIds[0]],
        [date('Y-m-d H:i:s'), 'retrait', 12000.00, $compteIds[0]],
        [date('Y-m-d H:i:s'), 'paiement', 6000.00, $compteIds[0]],
        [date('Y-m-d H:i:s'), 'depot', 5000.00, $compteIds[1]],
        [date('Y-m-d H:i:s'), 'retrait', 7000.00, $compteIds[2]],
    ];

    $stmtTrx = $pdo->prepare("
        INSERT INTO transaction (date, typetransaction, montant, compteid) 
        VALUES (?, ?, ?, ?)
    ");
    foreach ($transactions as $trx) {
        $stmtTrx->execute($trx);
    }
    echo "✅ Transactions insérées\n";

    $pdo->commit();
    echo "🎉 Toutes les données ont été insérées avec succès.\n";

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Erreur pendant le seeding : " . $e->getMessage() . "\n");
}