<?php
session_start();

require_once '../control/Database.php'; 
require_once '../control/Authentication.php';

if(isset($_SESSION['user_id'])){
    header('Location: home.php');
    exit();
}

$database = new Database();
$pdo = $database->getConnection();

if(isset($_POST['login'])){

    $email      = $_POST['email'];
    $password   = $_POST['password'];

    $auth = new Authentication($pdo); 
    $res = $auth->login($email, $password);

    if($res['success'] == true){
        
        if($res['user']['IsBanned'] == 'yes'){
            echo '<script>alert("You are banned and cannot log in.");</script>';
        } else {
            $_SESSION['token'] = $res['token'];
            $_SESSION['user']  = $res['user'];
            $_SESSION['user_id'] = $res['user_id'];
            header('Location: home.php');
            exit();
        }
    } else {
        echo '<script>alert("Verification failed! Please check your email and password.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/output.css">
    <title>Login</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-md py-4">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Article Hub</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="home.php" class="text-gray-600 hover:text-blue-600">Home</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="profile.php" class="text-gray-600 hover:text-blue-600">Profile</a></li>
                        <?php if ($_SESSION['user']['UserType'] === 'Admin'): ?>
                            <li><a href="dashboard.php" class="text-gray-600 hover:text-blue-600">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php" class="text-gray-600 hover:text-blue-600">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="text-gray-600 hover:text-blue-600">Login</a></li>
                        <li><a href="signup.php" class="text-gray-600 hover:text-blue-600">Signup</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-12">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Log In</h2>
            <form method="POST" action="login.php" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email_input" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password_input" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required>
                </div>
                <div>
                    <button type="submit" name="login" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                        Log In
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="#" class="text-sm text-blue-600 hover:underline">Forgot your password?</a>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Don't have an account? <a href="signup.php" class="text-blue-600 hover:underline">Sign up</a></p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-md py-4 mt-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-600">
            <p>&copy; <?php echo date('Y'); ?> Article Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>