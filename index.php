<?php
// Connexion à la base literie
$dsn = "mysql:host=localhost;dbname=literie";
$db = new PDO($dsn, "root", "");

// Récupérer les recettes de la table matelas
$query = $db->query("SELECT id, name, dimension,description, picture, price FROM matelas");
// Le paramètre PDO::FETCH_ASSOC permet de ne récupérer les résultats qu'au format tableau associatif et non les deux
$matelas = $query->fetchAll(PDO::FETCH_ASSOC);

// Inclure le template header
include("templates/header.php");
?>
<h1>CATALOGUE</h1>
<div class="matelas">
    <?php
    foreach ($matelas as $matela) {
    ?>
    <div class="flex">
        <div class="matela">
    
            <img src="img/matelas/<?= $matela["picture"] ?>" alt="">

        </div>
        <div class="right">
            <h2>
                <a href="matelas.php?id=<?= $matela["id"] ?>"><?= $matela["name"] ?></a>
            </h2>
            <p>Dimension: <?= $matela["dimension"] ?></p>
            
            <p>Description: <?= $matela["description"] ?></p>
            <p>Price: <?= $matela["price"] ?></p>
      


    
        </div>
</div>
    <?php
    }
    ?>
</div>
<!-- Bouton pour rediriger vers add.php -->
<div style="text-align: center;">
    <a href="add.php"><button>Ajouter une nouvelle matelas</button></a>
</div>

<?php
// Inclure le template footer
include("templates/footer.php");
?>