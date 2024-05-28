<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Double Navbar Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="font-sans">
    
    <!-- Main Header -->
    <header class="fixed-headerr flex bg-blue-700 text-white flex justify-between items-center p-4">
        <div class="logo">
            <h2 class="text-background">TaskMaster</h2>
        </div>
        <nav class="nav flex space-x-6 ">
            <a href="/backend/main.php" class="hover:underline">Home</a>
            <a href="/task_master/backend/aboutus.php" class="hover:underline">About Us</a>
            <a href="#" class="hover:underline">Contacts</a>
            <?php if (isset($_SESSION['role_type']) && $_SESSION['role_type'] == "admin"): ?>
        <a href="/backend/categories.php" class="hover:underline">Categories</a>
        <a href="/task_master/backend/tasks.php" class="hover:underline">Tasks of User</a>
        <a href="/task-management/users.php" class="hover:underline">Users</a>
    <?php elseif (isset($_SESSION['role_type']) && $_SESSION['role_type'] == "user"): ?>
        <a href="/task_master/backend/mytasks.php" class="hover:underline">MyTasks</a>
    <?php endif; ?>


        </nav>
        <div class="flex relative">
            <div class="voltage-button ">
            <?php if(isset($_SESSION['username'])): ?>
                <div class="voltage-button flex bg-bleu-700 text-white">
                    <h2 class="p-4 space-x-6"> Welcome <?php echo $_SESSION['username']; ?></h2>
                    <button><a href="./logout.php">Logout</a></button>
                    
                <?php else: ?>
                <button><a href="./login.php">Login</a></button>
               
               
            <div class="voltage-button flex bg-blue-700 text-white">

                <button><a href="./register.php">SingUp</a></button>
               
                <?php endif; ?>

            </div>
        </div>
    </header>
</body>

</html>
