<?php
session_start();

// Vérifie si l'admin est connecté
if (!isset($_SESSION["admin_login"])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $type = $_POST["type"];
    $unite = $_POST["unite"];

    // Insertion du nouveau capteur dans la BDD
    $stmt = $pdo->prepare("INSERT INTO Capteur (nom, type, unite) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $type, $unite]);

    // Redirige vers admin.php après l’ajout
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajouter un Capteur</title>
</head>
<body>
    <h1>Ajouter un nouveau Capteur</h1>

    <form method="post">
        <label>Nom :</label>
        <input type="text" name="nom" required><br>

        <label>Type :</label>
        <input type="text" name="type" required><br>

        <label>Unité :</label>
        <input type="text" name="unite" required><br>

        <button type="submit">Ajouter</button>
    </form>

    <p><a href="admin.php">Retour à l'administration</a></p>
</body>
</html>


