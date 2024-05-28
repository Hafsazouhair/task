<?php
require_once '../connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $ID_role = 2; // default role: user

    $sql = "INSERT INTO users (username, email, password, ID_role) VALUES (:username, :email, :password, :ID_role)";
    $stmt = $con->prepare($sql);

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password); // Using plain text password for demo purposes
    $stmt->bindParam(':ID_role', $ID_role);

    if ($stmt->execute()) {
        echo "Registration successful";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 items-center justify-center h-screen">
<?php include './header.php' ?>
<main class="flex flex-col items-center justify-center mt-20"> 
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-6">Inscription</h1>
        <form method="post" action="register.php" class="space-y-4">
            <input type="text" name="username" id="username" placeholder="Nom d'utilisateur" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"><br>
            <input type="email" name="email" id="email" placeholder="Email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"><br>
            <input type="password" name="password" id="password" placeholder="Mot de passe" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"><br>
            <input type="submit" name="submit" value="S'inscrire" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 cursor-pointer"><br>
        </form>
        <div class="mt-6 text-center">
            <h1 class="text-xl font-bold mb-4">Ou</h1>
            <a href="login.php" class="text-blue-600 hover:underline">Se connecter</a>
        </div>
    </div>
    </main>
</body>
</html>