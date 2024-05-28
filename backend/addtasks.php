<?php
session_start();

// Inclure le fichier qui contient la classe Crud et initialiser une instance
require "../connexion.php";

class Crud {
    private $con;

    public function __construct($dbname, $host, $user, $password) {
        try {
            $this->con = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connexion failed: " . $e->getMessage();
            die();
        }
    }

    public function insertTache($task_title, $task_description, $due_date, $ID_categorie, $ID_user, $priority) {
        try {
            $stmt = $this->con->prepare('INSERT INTO tasks (task_title, task_description, due_date, ID_categorie, ID_user, priority) VALUES (:task_title, :task_description, :due_date, :ID_categorie, :ID_user, :priority)');
            $stmt->bindValue(':task_title', $task_title);
            $stmt->bindValue(':task_description', $task_description);
            $stmt->bindValue(':due_date', $due_date);
            $stmt->bindValue(':ID_categorie', $ID_categorie);
            $stmt->bindValue(':ID_user', $ID_user);
            $stmt->bindValue(':priority', $priority);
            if ($stmt->execute()) {
                header("Location: Home.php");
                exit(); // Assurez-vous de terminer le script après la redirection
            } else {
                echo "Erreur lors de la création de la tâche.";
            }
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    public function getCategories() {
        try {
            $stmt = $this->con->prepare('SELECT ID_categorie, category_name FROM categories');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            return [];
        }
    }
}

// Mettez ici le mot de passe correct ou utilisez un utilisateur avec le bon mot de passe
$con = new Crud('gestion-des-taches', 'localhost', 'root', 'votre_mot_de_passe');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['ID_user'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les catégories depuis la base de données
$categories = $con->getCategories();

if (isset($_POST['creer-tache'])) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $dateEcheance = $_POST['dateEcheance'];
    $ID_categorie = $_POST['ID_categorie'];
    $ID_user = $_SESSION['ID_user']; // Utiliser l'ID utilisateur de la session
    $priority = $_POST['priority']; // Assuming you have a priority field

    // Appel de la méthode pour insérer la tâche
    $con->insertTache($titre, $description, $dateEcheance, $ID_categorie, $ID_user, $priority);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Tâche</title>
    <link href="./style.css" rel="stylesheet">
</head>

<body>
<?php include 'navbar.php'; ?>

<div class="container mx-auto p-10 mt-20">
    <h1 class="text-2xl font-bold mb-5 ml-5">Créer une Tâche</h1>
    <form action="" method="POST" class="bg-white shadow-md rounded px-8 pt-6 mt-10 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="titre">
                Titre
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="titre" type="text" name="titre" placeholder="Titre de la tâche" required="">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Description
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" placeholder="Description de la tâche" required=""></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="dateEcheance">
                Date d'Échéance
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="dateEcheance" type="date" name="dateEcheance" required="">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="id_categorie">
                Catégorie
            </label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="id_categorie" name="id_categorie" required="">
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= $categorie['ID_categorie'] ?>"><?= $categorie['category_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="priority">
                Priorité
            </label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="priority" name="priority" required="">
                <option value="low">Faible</option>
                <option value="medium">Moyenne</option>
                <option value="high">Haute</option>
            </select>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-[#7a4a82] text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="creer-tache">
                Créer Tâche
            </button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
