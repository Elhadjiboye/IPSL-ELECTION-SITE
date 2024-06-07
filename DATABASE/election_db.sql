-- Création de la base de données
CREATE DATABASE IF NOT EXISTS election_db;
USE election_db;

-- Table admin
CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nom_admin VARCHAR(255) COLLATE utf8mb4_general_ci,
    prenom_admin VARCHAR(255) COLLATE utf8mb4_general_ci,
    mot_de_passe_admin VARCHAR(255) COLLATE utf8mb4_general_ci
);

-- Table candidat
CREATE TABLE candidat (
    id_candidat INT AUTO_INCREMENT PRIMARY KEY,
    nom_candidat VARCHAR(255) COLLATE utf8mb4_general_ci,
    prenom_candidat VARCHAR(255) COLLATE utf8mb4_general_ci,
    mail_candidat VARCHAR(255) COLLATE utf8mb4_general_ci,
    programme_detude VARCHAR(255) COLLATE utf8mb4_general_ci,
    nombre_de_votant INT,
    id_election INT,
    FOREIGN KEY (id_election) REFERENCES election(id_election) ON DELETE CASCADE
);

-- Table electeur
CREATE TABLE electeur (
    id_electeur INT AUTO_INCREMENT PRIMARY KEY,
    nom_electeur VARCHAR(255) COLLATE utf8mb4_general_ci,
    prenom_electeur VARCHAR(255) COLLATE utf8mb4_general_ci,
    mail_electeur VARCHAR(255) COLLATE utf8mb4_general_ci,
    mot_de_passe_electeur VARCHAR(255) COLLATE utf8mb4_general_ci
);

-- Table vote
CREATE TABLE vote (
    id_vote INT AUTO_INCREMENT PRIMARY KEY,
    date_vote DATE,
    heure_vote TIMESTAMP,
    type_vote VARCHAR(255) COLLATE utf8mb4_general_ci,
    id_candidat INT,
    id_electeur INT,
    FOREIGN KEY (id_candidat) REFERENCES candidat(id_candidat) ON DELETE CASCADE,
    FOREIGN KEY (id_electeur) REFERENCES electeur(id_electeur) ON DELETE CASCADE
);

-- Table election
CREATE TABLE election (
    id_election INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) COLLATE utf8mb4_general_ci,
    date_debut DATE,
    date_fin DATE,
    type_election VARCHAR(255) COLLATE utf8mb4_general_ci
);

-- Table resultat
CREATE TABLE resultat (
    id_resultat INT AUTO_INCREMENT PRIMARY KEY,
    nombre_vote INT,
    pourcentage INT,
    id_election INT,
    id_candidat INT,
    FOREIGN KEY (id_election) REFERENCES election(id_election) ON DELETE CASCADE,
    FOREIGN KEY (id_candidat) REFERENCES candidat(id_candidat) ON DELETE CASCADE
);

-- Création d'un utilisateur avec des privilèges limités pour l'application
CREATE USER 'dark'@'localhost' IDENTIFIED BY 'dark';
GRANT SELECT, INSERT, UPDATE, DELETE ON election_db.* TO 'dark'@'localhost';

-- Activation des triggers, index, vues, et autres contraintes si nécessaire
-- (Ajoutez-les en fonction de vos besoins spécifiques)

-- Création d'une vue pour obtenir le nombre total de votes par candidat
CREATE VIEW view_total_votes AS
SELECT c.id_candidat, c.nom_candidat, c.prenom_candidat, COUNT(v.id_vote) AS total_votes
FROM candidat c
LEFT JOIN vote v ON c.id_candidat = v.id_candidat
GROUP BY c.id_candidat, c.nom_candidat, c.prenom_candidat;

-- Création d'une vue pour obtenir le pourcentage de votes par candidat
CREATE VIEW view_percentage_votes AS
SELECT v.id_candidat, v.id_election, c.nom_candidat, c.prenom_candidat,
    COUNT(v.id_vote) AS total_votes,
    (COUNT(v.id_vote) / e.nombre_de_votant) * 100 AS percentage_votes
FROM vote v
JOIN candidat c ON v.id_candidat = c.id_candidat
JOIN election e ON v.id_election = e.id_election
GROUP BY v.id_candidat, v.id_election, c.nom_candidat, c.prenom_candidat;

-- Création d'un trigger pour mettre à jour le nombre de votants chaque fois qu'un vote est ajouté
CREATE TRIGGER update_voters_count
AFTER INSERT ON vote
FOR EACH ROW
UPDATE candidat SET nombre_de_votant = nombre_de_votant + 1 WHERE id_candidat = NEW.id_candidat;

-- Création d'un trigger pour mettre à jour le pourcentage de votes chaque fois qu'un vote est ajouté
CREATE TRIGGER update_percentage_votes
AFTER INSERT ON vote
FOR EACH ROW
UPDATE resultat SET nombre_vote = nombre_vote + 1, pourcentage = (nombre_vote / (SELECT nombre_de_votant FROM election WHERE id_election = NEW.id_election)) * 100 WHERE id_candidat = NEW.id_candidat AND id_election = NEW.id_election;

-- Création d'un trigger pour mettre à jour le nombre de votants chaque fois qu'un vote est supprimé
CREATE TRIGGER decrease_voters_count
AFTER DELETE ON vote
FOR EACH ROW
UPDATE candidat SET nombre_de_votant = nombre_de_votant - 1 WHERE id_candidat = OLD.id_candidat;

-- Création d'un trigger pour mettre à jour le pourcentage de votes chaque fois qu'un vote est supprimé
CREATE TRIGGER decrease_percentage_votes
AFTER DELETE ON vote
FOR EACH ROW
UPDATE resultat SET nombre_vote = nombre_vote - 1, pourcentage = (nombre_vote / (SELECT nombre_de_votant FROM election WHERE id_election = OLD.id_election)) * 100 WHERE id_candidat = OLD.id_candidat AND id_election = OLD.id_election;


-- Création d'un trigger pour mettre à jour le pourcentage de votes chaque fois qu'un vote est ajouté
CREATE TRIGGER update_percentage_votes
AFTER INSERT ON vote
FOR EACH ROW
UPDATE resultat 
SET nombre_vote = nombre_vote + 1, 
    pourcentage = (nombre_vote / (SELECT nombre_de_votant FROM election WHERE id_election = NEW.id_election)) 
WHERE id_candidat = NEW.id_candidat AND id_election = NEW.id_election;

-- Création d'un trigger pour valider l'adresse e-mail avant l'insertion
DELIMITER //
CREATE TRIGGER validate_email
BEFORE INSERT ON electeur
FOR EACH ROW
BEGIN
    IF NEW.mail_electeur NOT REGEXP '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\\.[A-Z|a-z]{2,4}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Adresse e-mail non valide';
    END IF;
END;
//
DELIMITER ;

-- Ajouter la colonne id_election à la table vote
ALTER TABLE vote
ADD COLUMN id_election INT,
ADD FOREIGN KEY (id_election) REFERENCES election(id_election);

-- Ajouter la colonne id_election à la table resultat
ALTER TABLE resultat
ADD COLUMN id_election INT,
ADD FOREIGN KEY (id_election) REFERENCES election(id_election);

ALTER TABLE election
ADD COLUMN nombre_de_votant INT;

INSERT INTO admin (nom_admin, prenom_admin, mot_de_passe_admin) 
VALUES ('Dark', 'Dark', SHA2('dark', 256));
