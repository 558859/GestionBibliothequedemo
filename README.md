# Gestion Bibliothèque Universitaire

Application PHP simple de gestion d'une bibliothèque universitaire avec SQL Server, PDO `pdo_sqlsrv`, Bootstrap local et Bootstrap Icons local.

## Installation de la base

1. Ouvrir SQL Server Management Studio ou Azure Data Studio.
2. Exécuter le script :
   `database/gestion_bibliotheque.sql`
3. Le script crée la base `GestionBibliothequedemo`, les tables `categories`, `livres`, `etudiants`, `emprunts`, les clés étrangères et quelques données de test.

## Configuration PHP

La connexion est centralisée dans :

`config/database.php`

Vérifier si besoin :

- le serveur SQL Server : `localhost\SQLEXPRESS`
- la base : `GestionBibliothequedemo`
- l'utilisateur et le mot de passe
- l'extension PHP `pdo_sqlsrv`

## Lancement

Avec WampServer, placer le projet dans `C:\wamp64\www\gbibliotheque`, puis ouvrir :

`http://localhost/gbibliotheque/`

Les assets Bootstrap, Bootstrap Icons et Chart.js sont chargés localement. Aucun CDN n'est nécessaire.
