-- MySQL converted schema from PostgreSQL

DROP TABLE IF EXISTS transaction;
DROP TABLE IF EXISTS compte;
DROP TABLE IF EXISTS users;

-- Table: users
CREATE TABLE users (
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
);

-- Table: compte
CREATE TABLE compte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) NOT NULL UNIQUE,
    datecreation DATETIME DEFAULT CURRENT_TIMESTAMP,
    solde DECIMAL(15,2) DEFAULT 0.00,
    numerotel VARCHAR(20) NOT NULL,
    typecompte ENUM('principal', 'secondaire') NOT NULL,
    userid INT NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE
);

-- Table: transaction
CREATE TABLE transaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    typetransaction ENUM('depot', 'retrait', 'paiement') NOT NULL,
    montant DECIMAL(15,2) NOT NULL,
    compteid INT NOT NULL,
    FOREIGN KEY (compteid) REFERENCES compte(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Données : INSERTs convertis de COPY

-- Data: users
INSERT INTO users (id, nom, prenom, login, password, numerocarteidentite, photorecto, photoverso, adresse, typeuser) VALUES
(2, 'Niang', 'Madié', 'die', 'passer123', '21234567890', NULL, NULL, NULL, 'client'),
(1, 'Niang', 'aidasa', 'aida', 'passer', '21388888880', NULL, NULL, NULL, 'client');

-- Data: compte
INSERT INTO compte (id, numero, datecreation, solde, numerotel, typecompte, userid) VALUES
(36, 'CPT-687a26cdd829f', '2025-07-18 10:49:49', 30000.00, '775159909', 'secondaire', 2),
(37, 'CPT-687a2db6c7652', '2025-07-18 11:19:18', 5000.00, '773452800', 'secondaire', 2),
(2, '2', '2024-12-12 00:00:00', 465000.00, '778801947', 'principal', 2);

-- Data: transaction
INSERT INTO transaction (id, date, typetransaction, montant, compteid) VALUES
(2, '2024-12-12 00:00:00', 'depot', 10000.00, 2),
(1, '2025-07-12 00:00:00', 'retrait', 12000.00, 2),
(11, '2025-07-12 00:00:00', 'retrait', 50888.00, 2),
(3, '2025-07-12 00:00:00', 'depot', 500.00, 2),
(4, '2025-07-12 00:00:00', 'paiement', 6000.00, 2),
(5, '2025-07-14 00:00:00', 'depot', 1000.00, 2),
(6, '2025-07-20 00:00:00', 'retrait', 7000.00, 2),
(7, '2025-06-12 00:00:00', 'depot', 7900.00, 2),
(8, '2025-07-12 00:00:00', 'depot', 22222.00, 2),
(9, '2025-07-01 00:00:00', 'paiement', 322.00, 2),
(10, '2025-07-12 00:00:00', 'depot', 7770.00, 2),
(12, '2002-06-20 00:00:00', 'depot', 37890.00, 2),
(13, '2000-03-12 00:00:00', 'paiement', 12345.00, 2),
(15, '2024-09-12 00:00:00', 'paiement', 40000.00, 2);

-- Fix AUTO_INCREMENT values to continue at correct next ID
ALTER TABLE users AUTO_INCREMENT = 3;
ALTER TABLE compte AUTO_INCREMENT = 38;
ALTER TABLE transaction AUTO_INCREMENT = 16;
 