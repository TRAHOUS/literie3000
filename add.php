<?php
if (!empty($_POST)) {
    // Le formulaire est envoyé !
    // Utilisation de la fonction strip_tags pour supprimer d'éventuelles balises HTML qui ce seraient glissées dans le champ input et pallier à la faille XSS
    // Utilisation de la fonction trim pour supprimer d'éventuels espaces en début et fin de chaine
    $name = trim(strip_tags($_POST["name"]));
    $description = trim(strip_tags($_POST["description"]));
    $dimension = trim(strip_tags($_POST["dimension"]));
    $price = trim(strip_tags($_POST["price"]));

    $errors = [];

    // Valider que le champ name est bien renseigné
    if (empty($name)) {
        $errors["name"] = "La saisie de la marque est obligatoire";
    }

    // Gestion de l'upload de la photo de notre recette
    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] === UPLOAD_ERR_OK) {
        // Le fichier avec l'attribut name qui vaut picture existe et il n'y a pas eu d'erreur pendant l'upload
        $fileTmpPath = $_FILES["picture"]["tmp_name"];
        $fileName = $_FILES["picture"]["name"];
        $fileType = $_FILES["picture"]["type"];

        // On génère un nouveau nom de fichier pour ne pas se préoccuper des espaces, caractères accentués mais aussi si des personnes uploadent plusieurs images ayant le même nom
        $fileNameArray = explode(".", $fileName);
        // La fonction end est très pratique pour récupérer le dernier élément d'un tableau
        $fileExtension = end($fileNameArray);
        // L'ajout de time() permet d'être sûr d'avoir un hash unique
        // La fonction md5 permet de générer un hash à partir d'une chaine donnée
        $newFileName = md5($fileName . time()) . "." . $fileExtension;

        // Attention à vérifier que le dossier de destination est bien créé au préalable
        $fileDestPath = "./img/matelas/{$newFileName}";

        $allowedTypes = array("image/jpeg", "image/png", "image/webp");
        if (in_array($fileType, $allowedTypes)) {
            // Le type de fichier est bien valide, on peut donc ajouter le fichier à notre serveur
            move_uploaded_file($fileTmpPath, $fileDestPath);
        } else {
            // Le type de fichier est incorrect
            $errors["picture"] = "Le type de fichier est incorrect (.jpg, .png ou .webp requis)";
        }
    }

    // Valider que le champ price est bien renseigné
    if (empty($price)) {
        $errors["price"] = "Le prix est obligatoire";
    }

    // Requête d'insertion en BDD de la recette s'il n'y a aucune erreur
    if (empty($errors)) {
        // Connexion à la base literie
        $dsn = "mysql:host=localhost;dbname=literie";
        $db = new PDO($dsn, "root", "");

        // La valeur attendue pour les durées est en seconde et non en minute

        $query = $db->prepare("INSERT INTO matelas (name, picture, description, dimension, price) VALUES (:name, :picture, :description, :dimension, :price)");
        $query->bindParam(":name", $name);
        $query->bindParam(":picture", $newFileName);
        $query->bindParam(":description", $description);
        $query->bindParam(":dimension", $dimension);
        $query->bindParam(":price", $price);

        if ($query->execute()) {
            // La requête s'est bien déroulée, donc on redirige l'utilisateur vers la page d'accueil
            header("Location: index.php");
        }
    }
}

include("templates/header.php");
?>
<h1>Ajouter une matelas</h1>
<!-- Lorsque l'attribut action est vide, les données du formulaire sont envoyées à la même page -->
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="inputName">Marque de matelas :</label>
        <input type="text" id="inputName" name="name" value="<?= isset($name) ? $name : "" ?>">
        <?php
        if (isset($errors["name"])) {
        ?>
            <span class="info-error"><?= $errors["name"] ?></span>
        <?php
        }
        ?>
    </div>
    <div class="form-group">
        <label for="inputPicture">Photo de la matelas:</label>
        <input type="file" id="inputPicture" name="picture">
        <?php
        if (isset($errors["picture"])) {
        ?>
            <span class="info-error"><?= $errors["picture"] ?></span>
        <?php
        }
        ?>
    </div>
    <div class="form-group">
        <label for="textareaDescription">Description :</label>
        <textarea name="description" id="textareaDescription" cols="30" rows="10"><?= isset($description) ? $description : "" ?></textarea>
    </div>
    <div class="form-group">
        <label for="selectdimension">Choisissez une dimensions :</label>
        <select name="dimension" id="selectdimension">
            <option value="90*190" <?= isset($dimension) && $dimension === "90*190" ? "selected" : "" ?>>90*190</option>
            <option value="140*190" <?= isset($dimension) && $dimension === "140*190" ? "selected" : "" ?>>140*190</option>
            <option value="160*200" <?= isset($dimension) && $dimension === "160*200" ? "selected" : "" ?>>160*200</option>
            <option value="180*200" <?= isset($dimension) && $dimension === "180*200" ? "selected" : "" ?>>180*200</option>
            <option value="200*200" <?= isset($dimension) && $dimension === "200*200" ? "selected" : "" ?>>200*200</option>


        </select>
    </div>
    <div class="form-group">
        <label for="inputPrice">Prix :</label>
        <input type="number" step="0.01" id="inputPrice" name="price" value="<?= isset($price) ? $price : "" ?>">
        <?php
        if (isset($errors["price"])) {
        ?>
            <span class="info-error"><?= $errors["price"] ?></span>
        <?php
        }
        ?>
    </div>

    <input type="submit" value="Ajouter" class="btn-matelas">
</form>
<?php
include("templates/footer.php");
?>
