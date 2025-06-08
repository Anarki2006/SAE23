<?php
session_start();
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

if (!isset($_GET["nom"])) {
    die("Nom de salle manquant.");
}

$nom = $_GET["nom"];
$stmt = $pdo->prepare("DELETE FROM Salle WHERE nom = ?");
$stmt->execute([$nom]);

header("Location: admin.php");
exit();
?>


