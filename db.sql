USE master;
GO

CREATE DATABASE GestionBibliothequedemo;
GO

CREATE LOGIN bibli_user WITH PASSWORD = 'BibliPassword123!';
GO

USE GestionBibliothequedemo;
GO

CREATE USER bibli_user FOR LOGIN bibli_user;
GO

ALTER ROLE db_owner ADD MEMBER bibli_user;
GO

CREATE TABLE categories (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nom NVARCHAR(100) NOT NULL UNIQUE
);
GO

CREATE TABLE livres (
    id INT IDENTITY(1,1) PRIMARY KEY,
    titre NVARCHAR(200) NOT NULL,
    auteur NVARCHAR(150) NOT NULL,
    categorie_id INT NOT NULL,
    annee INT NULL,
    quantite_disponible INT NOT NULL,

    CONSTRAINT FK_livres_categories 
        FOREIGN KEY (categorie_id) REFERENCES categories(id),

    CONSTRAINT CK_livres_quantite 
        CHECK (quantite_disponible >= 0)
);
GO

CREATE TABLE etudiants (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nom NVARCHAR(100) NOT NULL,
    prenom NVARCHAR(100) NOT NULL,
    numero_etudiant NVARCHAR(50) NOT NULL UNIQUE,
    filiere NVARCHAR(120) NOT NULL,
    contact NVARCHAR(150) NOT NULL
);
GO

CREATE TABLE emprunts (
    id INT IDENTITY(1,1) PRIMARY KEY,
    livre_id INT NOT NULL,
    etudiant_id INT NOT NULL,
    date_emprunt DATE NOT NULL,
    date_retour_prevue DATE NOT NULL,
    est_retourne BIT NOT NULL DEFAULT 0,

    CONSTRAINT FK_emprunts_livres 
        FOREIGN KEY (livre_id) REFERENCES livres(id),

    CONSTRAINT FK_emprunts_etudiants 
        FOREIGN KEY (etudiant_id) REFERENCES etudiants(id),

    CONSTRAINT CK_emprunts_dates 
        CHECK (date_retour_prevue >= date_emprunt)
);
GO

INSERT INTO categories (nom) VALUES
(N'Informatique'),
(N'Roman'),
(N'Science Fiction'),
(N'Développement'),
(N'Histoire'),
(N'Mathématiques'),
(N'Économie'),
(N'Littérature africaine');
GO

INSERT INTO livres (titre, auteur, categorie_id, annee, quantite_disponible) VALUES
(N'Deep Learning and Mathematics Fundamentals', N'Jean Kouamé', 1, 2022, 4),
(N'Maîtrise de ChatGPT', N'Amadou Diallo', 1, 2024, 3),
(N'Algorithmes et structures de données', N'Robert Sedgewick', 1, 2011, 5),
(N'Une si longue lettre', N'Mariama Bâ', 8, 1979, 6),
(N'L’Enfant noir', N'Camara Laye', 8, 1953, 4),
(N'Le Petit Prince', N'Antoine de Saint-Exupéry', 2, 1943, 7),
(N'1984', N'George Orwell', 3, 1949, 2),
(N'Atomic Habits', N'James Clear', 4, 2018, 3),
(N'Histoire générale de l’Afrique', N'UNESCO', 5, 1980, 2),
(N'Mathématiques pour l’université', N'Serge Lang', 6, 2005, 4),
(N'Introduction à l’économie', N'Paul Samuelson', 7, 2010, 3);
GO

INSERT INTO etudiants (nom, prenom, numero_etudiant, filiere, contact) VALUES
(N'Diallo', N'Saikou', N'ETU-2026-001', N'Génie Informatique', N'611602924'),
(N'Attoumani', N'Ibrahim', N'ETU-2026-002', N'Médecine', N'658554524'),
(N'Sadio', N'Kourouma', N'ETU-2026-003', N'Droit public', N'611252426'),
(N'Ousmane', N'Bah', N'ETU-2026-004', N'Génie Civil', N'623541256'),
(N'Diallo', N'Boubacar', N'ETU-2026-005', N'Math Informatique', N'611602924'),
(N'Camara', N'Aminata', N'ETU-2026-006', N'Administration des affaires', N'620145879'),
(N'Barry', N'Mamadou', N'ETU-2026-007', N'Économie et gestion', N'664785214'),
(N'Keita', N'Fatoumata', N'ETU-2026-008', N'Lettres modernes', N'622441103'),
(N'Condé', N'Mohamed', N'ETU-2026-009', N'Sciences politiques', N'657889900'),
(N'Sylla', N'Aïssatou', N'ETU-2026-010', N'Réseaux et télécommunications', N'625778812');
GO

INSERT INTO emprunts (livre_id, etudiant_id, date_emprunt, date_retour_prevue, est_retourne) VALUES
(1, 1, '2026-05-20', '2026-06-03', 0),
(2, 1, '2026-05-22', '2026-06-05', 0),
(4, 2, '2026-05-10', '2026-05-24', 1),
(6, 3, '2026-05-14', '2026-05-28', 1),
(7, 4, '2026-05-18', '2026-06-01', 0),
(8, 5, '2026-05-25', '2026-06-08', 0),
(3, 6, '2026-05-19', '2026-06-02', 0),
(5, 7, '2026-05-12', '2026-05-26', 1),
(9, 8, '2026-05-21', '2026-06-04', 0),
(10, 9, '2026-05-24', '2026-06-07', 0),
(11, 10, '2026-05-15', '2026-05-29', 0);
GO