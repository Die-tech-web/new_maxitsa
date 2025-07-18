<?php

require_once __DIR__ . '/../vendor/autoload.php';

function prompt(string $message): string {
    echo $message;
    return trim(fgets(STDIN));
}

function writeEnvIfNotExists(array $config): void {
    $envPath = __DIR__ . '/../.env';
    if (!file_exists($envPath)) {
        $env = <<<ENV
DB_DRIVER={$config['driver']}
DB_HOST={$config['host']}
DB_PORT={$config['port']}
DB_NAME={$config['dbname']}
DB_USER={$config['user']}
DB_PASSWORD={$config['pass']}
ROUTE_WEB=http://localhost:8000/

dns = "{$config['driver']}:host={$config['host']}; dbname={$config['dbname']};port={$config['port']}"
ENV;
        file_put_contents($envPath, $env);
        echo ".env généré avec succès à la racine du projet.\n";
    } else {
        echo "Le fichier .env existe déjà, aucune modification.\n";
    }
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
            echo "Base PostgreSQL `$dbName` créée.\nRelancez la migration pour créer les tables.\n";
                writeEnvIfNotExists([
                        'driver' => $driver,
                        'host' => $host,
                        'port' => $port,
                        'user' => $user,
                        'pass' => $pass,
                        'dbname' => $dbName
                    ]);            
            exit;
        } else {
            echo "ℹ Base PostgreSQL `$dbName` déjà existante.\n";
        }
    }

    $dsn = "$driver:host=$host;port=$port;dbname=$dbName";
    $pdo = new PDO($dsn, $user, $pass);
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

            // TRANSACTION
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
        echo "Ce script est uniquement adapté pour PostgreSQL.\n";
        exit;
    }

    foreach ($tables as $sql) {
        $pdo->exec($sql);
    }

    echo "Toutes les tables ont été créées avec succès dans `$dbName`.\n";

    writeEnvIfNotExists([
        'driver' => $driver,
        'host' => $host,
        'port' => $port,
        'user' => $user,
        'pass' => $pass,
        'dbname' => $dbName
    ]);

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
