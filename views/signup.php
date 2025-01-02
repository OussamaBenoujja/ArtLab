



<?php

require_once '../control/Database.php'; 
require_once '../control/Authentication.php';


$database = new Database();
$pdo = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $bday = htmlspecialchars(trim($_POST['bday']));
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $password = htmlspecialchars(trim($_POST['password']));
    $accountType = htmlspecialchars(trim($_POST['account_type']));

    try {
        
        $auth = new Authentication($pdo); // $pdo is the database connection


        $success = $auth->register($username, $firstName, $lastName, $email, $password, $accountType, $bday, "No Bio");

        if ($success) {
            
            echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Failed to register. Email or username may already be in use.');</script>";
        }
    } catch (Exception $e) {
        
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<main class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
    <h1 class="text-2xl font-semibold text-center mb-6">Create Account</h1>
    <form action='signup.php' method='POST' class="space-y-4">
        <div>
            <label for='username' class="block text-sm font-medium text-gray-700">UserName</label>
            <input type='text' name='username' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='email' class="block text-sm font-medium text-gray-700">Email</label>
            <input type='email' name='email' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='bday' class="block text-sm font-medium text-gray-700">Birth Date</label>
            <input type='date' name='bday' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='first_name' class="block text-sm font-medium text-gray-700">First Name</label>
            <input type='text' name='first_name' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='last_name' class="block text-sm font-medium text-gray-700">Last Name</label>
            <input type='text' name='last_name' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='password' class="block text-sm font-medium text-gray-700">Password</label>
            <input type='password' name='password' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='account_type' class="block text-sm font-medium text-gray-700">Account Type</label>
            <select name='account_type' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value='member'>Member</option>
                <option value='author'>Author</option>
            </select>
        </div>
        <button type='submit' name='signup' class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition duration-150">Sign Up</button>
    </form>
</main>

</body>
</html>