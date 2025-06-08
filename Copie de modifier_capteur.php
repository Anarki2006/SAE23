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

// Vérifie si un id de capteur est fourni en GET
if (!isset($_GET["id"])) {
    die("ID de capteur manquant.");
}

$id_capteur = $_GET["id"];

// Récupère les informations actuelles du capteur
$stmt = $pdo->prepare("SELECT * FROM Capteur WHERE id_capteur = ?");
$stmt->execute([$id_capteur]);
$capteur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$capteur) {
    die("Capteur non trouvé.");
}

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $type = $_POST["type"];
    $unite = $_POST["unite"];

    // Mise à jour du capteur dans la BDD
    $stmt = $pdo->prepare("UPDATE Capteur SET nom = ?, type = ?, unite = ? WHERE id_capteur = ?");
    $stmt->execute([$nom, $type, $unite, $id_capteur]);

    // Redirige vers admin.php après la modification
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Modifier Capteur</title>
</head>
<body>
    <h1>Modifier le Capteur</h1>

    <form method="post">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($capteur["nom"]); ?>" required><br>

        <label>Type :</label>
        <input type="text" name="type" value="<?php echo htmlspecialchars($capteur["type"]); ?>" required><br>

        <label>Unité :</label>
        <input type="text" name="unite" value="<?php echo htmlspecialchars($capteur["unite"]); ?>" required><br>

        <button type="submit">Modifier</button>
    </form>

    <p><a href="admin.php">Retour à l'administration</a></p>
</body>
</html>


