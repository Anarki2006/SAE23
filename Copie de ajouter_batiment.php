<?php
session_start();

// Vérifie si l'admin est connecté
if (!isset($_SESSION["admin_login"])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $login = $_POST["login"];
    $mdp = $_POST["mdp"];

    // Insertion dans la BDD
    $insert = $pdo->prepare("INSERT INTO Batiment (nom, login, mdp) VALUES (?, ?, ?)");
    $insert->execute([$nom, $login, $mdp]);

    // Redirige vers admin.php
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Bâtiment</title>
</head>
<body>
    <h1>Ajouter un nouveau Bâtiment</h1>

    <form method="post">
        <label>Nom :</label>
        <input type="text" name="nom" required><br>
        <label>Login :</label>
        <input type="text" name="login" required><br>
        <label>Mot de passe :</label>
        <input type="text" name="mdp" required><br>
        <button type="submit">Ajouter</button>
    </form>

    <p><a href="admin.php">Retour à l'administration</a></p>
</body>
</html>


