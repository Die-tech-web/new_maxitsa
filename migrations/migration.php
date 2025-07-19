<?php

require_once __DIR__ . '/../vendor/autoload.php';

function prompt(string $message): string {
    echo $message;
    return trim(fgets(STDIN));
}

function writeEnv(array $config): void {
    $envPath = __DIR__ . '/../.env';
    $env = <<<ENV
DB_DRIVER={$config['driver']}
DB_HOST={$config['host']}
DB_PORT={$config['port']}
DB_NAME={$config['dbname']}
DB_USERNAME={$config['user']}
DB_PASSWORD={$config['pass']}
DSN="{$config['driver']}:host={$config['host']};dbname={$config['dbname']};port={$config['port']}"
ENV;

    file_put_contents($envPath, $env);
    echo ".env généré/mis à jour avec succès.\n";
}

$driver = strtolower(prompt("Quel SGBD utiliser ? (mysql / pgsql) : "));
$host = prompt("Hôte (default: 127.0.0.1) : ") ?: "127.0.0.1";
$port = prompt("Port (default: 3306 ou 5432) : ") ?: ($driver === 'pgsql' ? "5432" : "3306");
$user = prompt("Utilisateur (default: root) : ") ?: "root";
$pass = prompt("Mot de passe : ");
$dbName = prompt("Nom de la base à créer : ");

try {
    $dsn = "$driver:host=$host;port=$port";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($driver === 'pgsql') {
        $check = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '$dbName'")->fetch();
        if (!$check) {
            $pdo->exec("CREATE DATABASE \"$dbName\"");
            echo "Base PostgreSQL `$dbName` créée.\n";
        } else {
            echo "Base PostgreSQL `$dbName` déjà existante.\n";
        }
    } elseif ($driver === 'mysql') {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Base MySQL `$dbName` créée ou existante.\n";
    } else {
        die("SGBD non supporté\n");
    }

    // Connexion à la base
    $dsnDb = "$driver:host=$host;port=$port;dbname=$dbName";
    $pdo = new PDO($dsnDb, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($driver === 'pgsql') {
        $tables = [
            "CREATE SEQUENCE IF NOT EXISTS users_id_seq START WITH 1 INCREMENT BY 1;",
            "CREATE SEQUENCE IF NOT EXISTS compte_id_seq START WITH 1 INCREMENT BY 1;",
            "CREATE SEQUENCE IF NOT EXISTS transaction_id_seq START WITH 1 INCREMENT BY 1;",

            "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY DEFAULT nextval('users_id_seq'),
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                login VARCHAR(50),
                password VARCHAR(255) NOT NULL,
                numerocarteidentite VARCHAR(50),
                photorecto VARCHAR(255),
                photoverso VARCHAR(255),
                adresse VARCHAR(255),
                typeuser VARCHAR(20) NOT NULL,
                CONSTRAINT users_login_key UNIQUE (login),
                CONSTRAINT users_numerocarteidentite_key UNIQUE (numerocarteidentite),
                CONSTRAINT users_typeuser_check CHECK (typeuser IN ('client', 'service_commercial'))
            );",

            "CREATE TABLE IF NOT EXISTS compte (
                id INTEGER PRIMARY KEY DEFAULT nextval('compte_id_seq'),
                numero VARCHAR(20) NOT NULL UNIQUE,
                datecreation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                solde NUMERIC(15,2) DEFAULT 0.00,
                numerotel VARCHAR(20) NOT NULL,
                typecompte VARCHAR(20) NOT NULL,
                userid INTEGER NOT NULL,
                CONSTRAINT compte_typecompte_check CHECK (typecompte IN ('principal', 'secondaire')),
                CONSTRAINT compte_userid_fkey FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE
            );",

            "CREATE TABLE IF NOT EXISTS transaction (
                id INTEGER PRIMARY KEY DEFAULT nextval('transaction_id_seq'),
                date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                typetransaction VARCHAR(20) NOT NULL,
                montant NUMERIC(15,2) NOT NULL,
                compteid INTEGER NOT NULL,
                CONSTRAINT transaction_typetransaction_check CHECK (typetransaction IN ('depot', 'retrait', 'paiement')),
                CONSTRAINT transaction_compteid_fkey FOREIGN KEY (compteid) REFERENCES compte(id) ON DELETE CASCADE
            );"
        ];
    } else {
        $tables = [
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                login VARCHAR(50) UNIQUE,
                password VARCHAR(255) NOT NULL,
                numerocarteidentite VARCHAR(50) UNIQUE,
                photorecto VARCHAR(255),
                photoverso VARCHAR(255),
                adresse VARCHAR(255),
                typeuser ENUM('client', 'service_commercial') NOT NULL
            );",

            "CREATE TABLE IF NOT EXISTS compte (
                id INT AUTO_INCREMENT PRIMARY KEY,
                numero VARCHAR(20) NOT NULL UNIQUE,
                datecreation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                solde DECIMAL(15,2) DEFAULT 0.00,
                numerotel VARCHAR(20) NOT NULL,
                typecompte ENUM('principal', 'secondaire') NOT NULL,
                userid INT NOT NULL,
                FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE
            );",

            "CREATE TABLE IF NOT EXISTS transaction (
                id INT AUTO_INCREMENT PRIMARY KEY,
                date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                typetransaction ENUM('depot', 'retrait', 'paiement') NOT NULL,
                montant DECIMAL(15,2) NOT NULL,
                compteid INT NOT NULL,
                FOREIGN KEY (compteid) REFERENCES compte(id) ON DELETE CASCADE
            );"
        ];
    }

    foreach ($tables as $sql) {
        $pdo->exec($sql);
    }

    echo "Tables créées avec succès dans `$dbName`.\n";

    writeEnv([
        'driver' => $driver,
        'host'   => $host,
        'port'   => $port,
        'user'   => $user,
        'pass'   => $pass,
        'dbname' => $dbName
    ]);

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
