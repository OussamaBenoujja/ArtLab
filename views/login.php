<?php
session_start();


require_once '../control/Database.php'; 
require_once '../control/Authentication.php';

$database = new Database();
$pdo = $database->getConnection();

if(isset($_POST['login'])){

    $email      = $_POST['email'];
    $password   = $_POST['password'];

    $auth = new Authentication($pdo); 
    $res = $auth->login($email, $password);
    if($res['success']==true){

        $_SESSION['token'] = $res['token'];
        $_SESSION['user']  = $res['user'];
        $_SESSION['user_id'] = $res['user_id'];
        header('Location: home.php');
    }else{
        echo 'verfication failed!';
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
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <main class="bg-white shadow-md rounded-lg p-8 w-96">
        <h2 class="text-2xl font-bold text-center mb-6">Log In</h2>
        <form method="POST" action="login.php" class="flex flex-col gap-4">
            <label for="email" class="font-semibold text-gray-700">Email</label>
            <input type="email" name="email" id="email_input" class="border-2 border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" required>

            <label for="password" class="font-semibold text-gray-700">Password</label>
            <input type="password" name="password" id="password_input" class="border-2 border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" required>

            <button type="submit" name="login" class="bg-blue-600 text-white font-semibold rounded-md p-2 hover:bg-blue-700 transition duration-200">Log In</button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            <a href="#" class="text-blue-600 hover:underline">Forgot your password?</a>
        </p>
    </main>

</body>
</html>