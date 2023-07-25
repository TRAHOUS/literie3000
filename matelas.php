<?php
$find = false;
$data = array("name" => "Recette introuvable");
if (isset($_GET["id"])) {
    // on sait qu'il y a un paramètre id dans l'url
    // MAIS pour autant ça ne garantit pas que l'id de la recette existe réellement
    // Connexion à la base literie
    $dsn = "mysql:host=localhost;dbname=literie";
    $db = new PDO($dsn, "root", "");

    // 1/ On prépare la requête SQL avec un paramètre pour palier à l'injection SQL
    $query = $db->prepare("SELECT * FROM matelas WHERE id = :id");
    // 2/ On donne des valeurs à nos paramètres
    $query->bindParam(":id", $_GET["id"], PDO::PARAM_INT);
    // 3/ On execute notre requête préalablement préparée
    $query->execute();
    $matela = $query->fetch(); // retourne un tableau associatif de la recette concernée ou false si pas de correspondance

    if ($matela) {
        $find = true;

        $data = $matela;
    }
}

// Inclure le template header
include("templates/header.php");
?>
<h1> <?= $data["name"] ?></h1>
<?php
if ($find) {
?>
<div class="flex">
  <div class="right">  <img src="img/matelas/<?= $data["picture"] ?>" alt="" class="matela-picture"></div>
  <div class="left">
    <p> <?= $data["description"] ?></p>
    <p>Prix : <?= $data["price"] ?>$</p>
    <p>Dimension : <?= $data["dimension"] ?></p>

  </div>
   <!-- Bouton de suppression -->
   <form action="delete.php" method="get">
        <input type="hidden" name="id" value="<?= $_GET["id"] ?>">
        <button type="submit" class="delete-btn">Supprimer le model</button>
    </form>


   <!-- Formulaire de modification -->
   <form method="post" action="update.php">
                <input type="hidden" name="id" value="<?php echo $matela["id"]; ?>">
                <label for="name">Marque de matelas : </label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($matela["name"]); ?>"><br>
                <label for="description">Description:</label>
                <textarea name="description"><?php echo htmlspecialchars($matela["description"]); ?></textarea><br>
                <label for="price">Prix:</label>
                <input type="number" name="price" value="<?php echo htmlspecialchars($matela["price"]); ?>"><br>
                
                <label for="dimension">Dimension:</label>
    <input type="text" name="dimension" value="<?= $matela["dimension"] ?>" required>

                <input type="submit" value="Modifier">
            </form>

  </div>

<?php
}

// Inclure le template footer
include("templates/footer.php");
?>