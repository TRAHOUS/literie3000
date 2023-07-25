<?php
if (isset($_GET["id"])) {
    // Connexion à la base de données Marmiton
    $dsn = "mysql:host=localhost;dbname=literie";
    $db = new PDO($dsn, "root", "");

    // Préparer la requête SQL pour supprimer la recette
    $query = $db->prepare("DELETE FROM matelas WHERE id = :id");

    // Lier les valeurs aux paramètres
    $query->bindParam(":id", $_GET["id"], PDO::PARAM_INT);

    // Exécuter la requête préparée
    $query->execute();

    // Rediriger vers la liste des recettes ou une autre page de votre choix
    header("Location: index.php");
    exit; // Assurez-vous de terminer le script ici pour éviter toute exécution supplémentaire
}
?>
