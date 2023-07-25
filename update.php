<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    // Assurez-vous que les champs nécessaires sont présents dans le formulaire
    if (isset($_POST["name"]) && isset($_POST["description"]) && isset($_POST["price"])) {
        // Connexion à la base de données Marmiton
        $dsn = "mysql:host=localhost;dbname=literie";
        $db = new PDO($dsn, "root", "");

        // Préparer la requête SQL pour mettre à jour la recette
        $query = $db->prepare("UPDATE matelas SET name = :name, description = :description, price = :price WHERE id = :id");

        // Lier les valeurs aux paramètres
        $query->bindParam(":id", $_POST["id"], PDO::PARAM_INT);
        $query->bindParam(":name", $_POST["name"], PDO::PARAM_STR);
        $query->bindParam(":description", $_POST["description"], PDO::PARAM_STR);
        $query->bindParam(":price", $_POST["price"], PDO::PARAM_INT);

        // Exécuter la requête préparée
        if ($query->execute()) {
            // Rediriger vers la page de la recette mise à jour ou toute autre page de votre choix
            header("Location: matelas.php
            ?id=" . $_POST["id"]);
            exit; // Assurez-vous de terminer le script ici pour éviter toute exécution supplémentaire
        } else {
            // En cas d'erreur lors de l'exécution de la requête
            echo "Une erreur s'est produite lors de la mise à jour de la recette.";
        }
    }
}
?>
