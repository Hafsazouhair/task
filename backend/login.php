<?php

require_once '../connexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT users.ID_user, users.username, users.password, users.email, roles.role_type 
            FROM users 
            JOIN roles ON users.ID_role = roles.ID_role 
            WHERE email = :email";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($password === $row['password']) { // Using plain text password for demo purposes
            // Set session variables for authentication
            $_SESSION['username'] = $row['username'];
            $_SESSION['role_type'] = $row['role_type'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['ID_user'] = $row['ID_user'];
            // Set cookies for user preferences (non-sensitive information)
            setcookie('username', $row['username'], time() + (86400 * 30), "/"); // 30 days
            // Commented out email cookie set line
             setcookie('email', $row['email'], time() + (86400 * 30), "/"); // 30 days
            setcookie('role_type', $row['role_type'], time() + (86400 * 30), "/"); // 30 days

            // Redirect based on user role
            if ($row['role_type'] == 'admin') {
                header("Location: header.php");
            } else {
                header("Location: header.php");
            }
            exit();
        } else {
            // Incorrect password
            echo "Invalid password.";
        }
    } else {
        // User not found
        echo "No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100  items-center justify-center h-screen">
    
<?php include './header.php' ?>

  <main class="flex flex-col items-center justify-center mt-20">
      <div class="bg-white p-8 rounded shadow-md w-96 ">
        <h1 class="text-2xl font-bold mb-4">Login</h1>
        <form method="post" action="login.php">
            <div class="mb-4">
                <input type="email" name="email" id="email" placeholder="Email" required class="border border-gray-300 rounded-md w-full px-3 py-2">
            </div>
            <div class="mb-4">
                <input type="password" name="password" id="password" placeholder="Password" required class="border border-gray-300 rounded-md w-full px-3 py-2">
            </div>
            <div class="mb-4">
                <input type="submit" name="submit" value="Login" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
            </div>
        </form>
        <h1 class="text-xl font-bold mb-2">Or</h1>
        <a href="register.php" class="text-blue-500 hover:underline">Register</a>
    </div>
</main>
</body>

