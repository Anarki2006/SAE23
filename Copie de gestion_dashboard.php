<?php
session_start();

if (!isset($_SESSION["gestion_login"])) {
    header("Location: gestion.php");
    exit();
}

$id_batiment = $_SESSION["id_batiment"];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Info du bâtiment
    $stmt = $pdo->prepare("SELECT * FROM Batiment WHERE id_batiment = ?");
    $stmt->execute([$id_batiment]);
    $batiment = $stmt->fetch(PDO::FETCH_ASSOC);

    // Salles du bâtiment
    $stmt = $pdo->prepare("SELECT * FROM Salle WHERE id_batiment = ?");
    $stmt->execute([$id_batiment]);
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Tableau de bord Gestionnaire</title>
<style>
table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
h2 { margin-top: 30px; }
</style>
</head>
<body>
<h1>Tableau de bord du Gestionnaire</h1>
<p>Connecté en tant que : <?php echo htmlspecialchars($_SESSION["gestion_login"]); ?></p>

<h2>Bâtiment</h2>
<p>ID : <?php echo htmlspecialchars($batiment["id_batiment"]); ?><br>
Nom : <?php echo htmlspecialchars($batiment["nom"]); ?></p>

<h2>Salles et statistiques</h2>
<?php foreach ($salles as $salle) : ?>
    <h3>Salle : <?php echo htmlspecialchars($salle["nom"]); ?> (<?php echo htmlspecialchars($salle["type"]); ?>)</h3>

    <table>
        <thead>
            <tr>
                <th>Capteur</th>
                <th>Type</th>
                <th>Unité</th>
                <th>Moyenne</th>
                <th>Min</th>
                <th>Max</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM Capteur WHERE nom LIKE ?");
        $stmt->execute(["%" . $salle["nom"] . "%"]);
        $capteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($capteurs as $cap) {
            $stmtStat = $pdo->prepare("SELECT AVG(valeur) AS moyenne, MIN(valeur) AS min, MAX(valeur) AS max FROM Mesure WHERE id_capteur = ?");
            $stmtStat->execute([$cap["id_capteur"]]);
            $stats = $stmtStat->fetch(PDO::FETCH_ASSOC);
        ?>
            <tr>
                <td><?php echo htmlspecialchars($cap["nom"]); ?></td>
                <td><?php echo htmlspecialchars($cap["type"]); ?></td>
                <td><?php echo htmlspecialchars($cap["unite"]); ?></td>
                <td><?php echo htmlspecialchars(round($stats["moyenne"], 2)); ?></td>
                <td><?php echo htmlspecialchars($stats["min"]); ?></td>
                <td><?php echo htmlspecialchars($stats["max"]); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php endforeach; ?>

<h2>Liste des Mesures de votre bâtiment</h2>
<table>
    <thead>
        <tr>
            <th>ID Mesure</th>
            <th>Valeur</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Nom Capteur</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Récupérer les mesures liées aux capteurs de ce bâtiment
        $sql = "SELECT m.id_mesure, m.valeur, m.date, m.heure, c.nom AS capteur_nom
                FROM Mesure m
                JOIN Capteur c ON m.id_capteur = c.id_capteur
                JOIN Salle s ON s.id_batiment = ?
                WHERE c.nom LIKE CONCAT('%', s.nom, '%')
                ORDER BY m.date DESC, m.heure DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_batiment]);
        $mesures = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($mesures as $mes) :
        ?>
            <tr>
                <td><?php echo htmlspecialchars($mes["id_mesure"]); ?></td>
                <td><?php echo htmlspecialchars($mes["valeur"]); ?></td>
                <td><?php echo htmlspecialchars($mes["date"]); ?></td>
                <td><?php echo htmlspecialchars($mes["heure"]); ?></td>
                <td><?php echo htmlspecialchars($mes["capteur_nom"]); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="gestion_logout.php">Se déconnecter</a></p>
</body>
</html>


