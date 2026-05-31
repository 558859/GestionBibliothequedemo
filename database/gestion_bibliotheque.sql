
BEGIN
    CREATE DATABASE GestionBibliothequedemo;
END
GO

USE GestionBibliothequedemo;
GO


CREATE TABLE dbo.categories (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    nom NVARCHAR(100) NOT NULL
);
GO

CREATE TABLE dbo.livres (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    titre NVARCHAR(200) NOT NULL,
    auteur NVARCHAR(150) NOT NULL,
    categorie_id INT NOT NULL,
    annee INT NULL,
    quantite_disponible INT NOT NULL CONSTRAINT CK_livres_quantite CHECK (quantite_disponible >= 0),
    CONSTRAINT FK_livres_categories FOREIGN KEY (categorie_id) REFERENCES dbo.categories(id)
);
GO

CREATE TABLE dbo.etudiants (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    nom NVARCHAR(100) NOT NULL,
    prenom NVARCHAR(100) NOT NULL,
    numero_etudiant NVARCHAR(50) NOT NULL,
    filiere NVARCHAR(120) NOT NULL,
    contact NVARCHAR(150) NOT NULL,
    CONSTRAINT UQ_etudiants_numero UNIQUE (numero_etudiant)
);
GO

CREATE TABLE dbo.emprunts (
    id INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
    livre_id INT NOT NULL,
    etudiant_id INT NOT NULL,
    date_emprunt DATE NOT NULL,
    date_retour_prevue DATE NOT NULL,
    est_retourne BIT NOT NULL CONSTRAINT DF_emprunts_est_retourne DEFAULT (0),
    CONSTRAINT FK_emprunts_livres FOREIGN KEY (livre_id) REFERENCES dbo.livres(id),
    CONSTRAINT FK_emprunts_etudiants FOREIGN KEY (etudiant_id) REFERENCES dbo.etudiants(id),
    CONSTRAINT CK_emprunts_dates CHECK (date_retour_prevue >= date_emprunt)
);
GO

INSERT INTO dbo.categories (nom) VALUES
(N'Informatique'),
(N'Mathématiques'),
(N'Littérature'),
(N'Science Fiction');
GO

INSERT INTO dbo.livres (titre, auteur, categorie_id, annee, quantite_disponible) VALUES
(N'Introduction aux bases de données', N'Jean Dupont', 1, 2021, 3),
(N'Algorithmique simple', N'Marie Laurent', 1, 2020, 2),
(N'Analyse mathématique', N'Paul Bernard', 2, 2018, 1),
(N'Le Petit Prince', N'Antoine de Saint-Exupéry', 3, 1943, 4),
(N'Dune', N'Frank Herbert', 4, 1965, 1);
GO

INSERT INTO dbo.etudiants (nom, prenom, numero_etudiant, filiere, contact) VALUES
(N'Diallo', N'Amina', N'ETU001', N'Informatique', N'amina.diallo@example.com'),
(N'Martin', N'Lucas', N'ETU002', N'Mathématiques', N'0601020304'),
(N'Nguyen', N'Emma', N'ETU003', N'Lettres modernes', N'emma.nguyen@example.com');
GO

INSERT INTO dbo.emprunts (livre_id, etudiant_id, date_emprunt, date_retour_prevue, est_retourne) VALUES
(1, 1, '2026-05-01', '2026-05-15', 1),
(2, 2, '2026-05-10', '2026-06-01', 0),
(5, 3, '2026-05-12', '2026-05-26', 0);
GO

UPDATE dbo.livres SET quantite_disponible = quantite_disponible - 1 WHERE id IN (2, 5);
GO
